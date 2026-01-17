<?php

namespace App\Http\Controllers;

use App\Models\Layoff;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LayoffController extends Controller
{
    /**
     * Display a listing of laid off employees.
     */
    public function index(Request $request)
    {
        $query = Layoff::with(['employee', 'processedBy']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('employee_name', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        // Date filter
        if ($request->filled('date_from')) {
            $query->where('layoff_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('layoff_date', '<=', $request->date_to);
        }

        $layoffs = $query->orderBy('layoff_date', 'desc')->paginate(15);

        return view('layoffs.index', compact('layoffs'));
    }

    /**
     * Show the form for creating a new layoff.
     */
    public function create(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can process layoffs.');
        }

        // Get employee_id from query parameter
        $employeeId = $request->get('employee_id');
        $employee = null;

        if ($employeeId) {
            $employee = Employee::findOrFail($employeeId);

            // Check if employee already laid off
            if ($employee->isLaidOff()) {
                return redirect()->route('employees.index')
                    ->with('error', 'This employee has already been laid off.');
            }
        }

        return view('layoffs.create', compact('employee'));
    }

    /**
     * Store a newly created layoff in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can process layoffs.');
        }

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'layoff_date' => 'required|date',
            'reason' => 'nullable|string',
            'layoff_letter' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        // Check if employee already laid off
        $employee = Employee::findOrFail($validated['employee_id']);
        if ($employee->isLaidOff()) {
            return back()->with('error', 'This employee has already been laid off.')
                ->withInput();
        }

        // Handle file upload
        if ($request->hasFile('layoff_letter')) {
            $file = $request->file('layoff_letter');
            $filename = time() . '_' . $file->getClientOriginalName();
            $validated['layoff_letter'] = $file->storeAs('layoff_letters', $filename, 'public');
        }

        $validated['processed_by'] = auth()->id();

        $layoff = Layoff::create($validated);

        // Update employee status to Layoff
        $layoff->employee->update(['status' => 'Layoff']);

        // Update all employee's contracts to Layoff status
        $layoff->employee->contracts()->update(['status' => 'Layoff']);

        return redirect()->route('layoffs.index')
            ->with('success', 'Employee ' . $employee->employee_name . ' has been processed for layoff.');
    }

    /**
     * Display the specified layoff.
     */
    public function show(Layoff $layoff)
    {
        $layoff->load(['employee', 'processedBy']);
        return view('layoffs.show', compact('layoff'));
    }

    /**
     * Show the form for editing the specified layoff.
     */
    public function edit(Layoff $layoff)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can edit layoffs.');
        }

        return view('layoffs.edit', compact('layoff'));
    }

    /**
     * Update the specified layoff in storage.
     */
    public function update(Request $request, Layoff $layoff)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can update layoffs.');
        }

        $validated = $request->validate([
            'layoff_date' => 'required|date',
            'reason' => 'nullable|string',
            'layoff_letter' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        // Handle file upload
        if ($request->hasFile('layoff_letter')) {
            $file = $request->file('layoff_letter');
            $filename = time() . '_' . $file->getClientOriginalName();
            $validated['layoff_letter'] = $file->storeAs('layoff_letters', $filename, 'public');
        }

        $layoff->update($validated);

        return redirect()->route('layoffs.index')
            ->with('success', 'Layoff record updated successfully.');
    }

    /**
     * Remove the specified layoff from storage (restore employee).
     */
    public function destroy(Layoff $layoff)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can delete layoffs.');
        }

        $employeeName = $layoff->employee->employee_name;

        // Delete file if exists
        if ($layoff->layoff_letter) {
            Storage::disk('public')->delete($layoff->layoff_letter);
        }

        // Change employee status back to Active
        $layoff->employee->update(['status' => 'Active']);

        // Restore all employee's contracts to Active status
        $layoff->employee->contracts()->update(['status' => 'Active']);

        $layoff->delete();

        return redirect()->route('layoffs.index')
            ->with('success', 'Layoff record deleted. Employee ' . $employeeName . ' has been restored to the employee list.');
    }

    /**
     * Permanently delete the employee and all associated data.
     */
    public function permanentDelete(Layoff $layoff)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can permanently delete employees.');
        }

        $employeeName = $layoff->employee->employee_name;
        $employee = $layoff->employee;

        // Delete layoff letter file if exists
        if ($layoff->layoff_letter) {
            Storage::disk('public')->delete($layoff->layoff_letter);
        }

        // Delete employee's CV file if exists
        if ($employee->file_cv) {
            Storage::disk('public')->delete($employee->file_cv);
        }

        // Delete all contract files associated with this employee
        foreach ($employee->contracts as $contract) {
            if ($contract->file_contract) {
                Storage::disk('public')->delete($contract->file_contract);
            }
        }

        // Delete the employee (cascade will delete contracts, layoff, etc.)
        $employee->delete();

        return redirect()->route('layoffs.index')
            ->with('success', 'Employee ' . $employeeName . ' and all associated data have been permanently deleted.');
    }
}

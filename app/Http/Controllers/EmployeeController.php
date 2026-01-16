<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees.
     */
    public function index(Request $request)
    {
        $query = Employee::with(['contracts' => function ($q) {
            $q->orderBy('start_date', 'desc');
        }]);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('employee_name', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhereHas('contracts', function ($q) use ($search) {
                        $q->where('nomor_kontrak', 'like', "%{$search}%");
                    });
            });
        }

        // Department filter
        if ($request->filled('department')) {
            $query->whereHas('contracts', function ($q) use ($request) {
                $q->where('department', $request->department);
            });
        }

        // Work location filter
        if ($request->filled('work_location')) {
            $query->whereHas('contracts', function ($q) use ($request) {
                $q->where('work_location', $request->work_location);
            });
        }

        $employees = $query->orderBy('employee_name', 'asc')->get();

        // By default, show active employees (Active, Permanent, Expiring Soon) - exclude expired and layoff
        $employees = $employees->filter(function ($employee) {
            $latestContract = $employee->contracts->first();

            if (!$latestContract) {
                return false;
            }

            // Exclude Layoff
            if ($latestContract->status === 'Layoff') {
                return false;
            }

            // Include Permanent employees (they never expire)
            if ($latestContract->status === 'Permanent') {
                return true;
            }

            // Exclude expired contracts
            if ($latestContract->end_date && $latestContract->end_date < now()) {
                return false;
            }

            return true;
        });

        // Get unique departments and work locations from contracts
        $departments = \App\Models\Contract::distinct()->pluck('department')->filter()->sort()->values();
        $workLocations = \App\Models\Contract::distinct()->pluck('work_location')->filter()->sort()->values();

        return view('employees.index', compact('employees', 'departments', 'workLocations'));
    }

    /**
     * Display employees with expired contracts.
     */
    public function expired(Request $request)
    {
        $query = Employee::with(['contracts' => function ($q) {
            $q->orderBy('start_date', 'desc');
        }]);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('employee_name', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhereHas('contracts', function ($q) use ($search) {
                        $q->where('nomor_kontrak', 'like', "%{$search}%");
                    });
            });
        }

        // Department filter
        if ($request->filled('department')) {
            $query->whereHas('contracts', function ($q) use ($request) {
                $q->where('department', $request->department);
            });
        }

        // Work location filter
        if ($request->filled('work_location')) {
            $query->whereHas('contracts', function ($q) use ($request) {
                $q->where('work_location', $request->work_location);
            });
        }

        $employees = $query->orderBy('employee_name', 'asc')->get();

        // Filter to show only employees with expired contracts (not laid off)
        $employees = $employees->filter(function ($employee) {
            $latestContract = $employee->contracts->first();

            if (!$latestContract) {
                return false;
            }

            // Exclude Layoff status
            if ($latestContract->status === 'Layoff') {
                return false;
            }

            // Exclude Permanent employees (they never expire)
            if ($latestContract->status === 'Permanent') {
                return false;
            }

            // Show only expired contracts (end_date must exist and be in the past)
            return $latestContract->end_date && $latestContract->end_date < now();
        });

        // Get unique departments and work locations from contracts
        $departments = \App\Models\Contract::distinct()->pluck('department')->filter()->sort()->values();
        $workLocations = \App\Models\Contract::distinct()->pluck('work_location')->filter()->sort()->values();

        return view('employees.expired', compact('employees', 'departments', 'workLocations'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can create employees.');
        }

        // Get data for contract form
        $departments = \App\Models\Contract::distinct()->pluck('department')->filter()->sort()->values();
        $workLocations = \App\Models\Contract::distinct()->pluck('work_location')->filter()->sort()->values();

        return view('employees.create', compact('departments', 'workLocations'));
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can create employees.');
        }

        $validated = $request->validate([
            'employee_name' => 'required|string|max:255',
            'nik' => 'required|string|max:50|unique:employees,nik',
            'nid' => 'nullable|string|max:16|unique:employees,nid',
            'birthplace' => 'required|string|max:255',
            'birthdate' => 'required|date|before:today',
            'TMT_awal' => 'nullable|date',
            'address' => 'required|string',
            'nomor_hp' => 'nullable|string|max:20',
            'file_cv' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        // Handle file upload
        if ($request->hasFile('file_cv')) {
            $file = $request->file('file_cv');
            $filename = time() . '_' . $file->getClientOriginalName();
            $validated['file_cv'] = $file->storeAs('cvs', $filename, 'public');
        }

        $employee = Employee::create($validated);

        // If AJAX request, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Employee created successfully!',
                'employee' => [
                    'id' => $employee->id,
                    'employee_name' => $employee->employee_name,
                    'nik' => $employee->nik,
                    'birthplace' => $employee->birthplace,
                    'birthdate' => $employee->birthdate->format('d M Y'),
                    'address' => $employee->address,
                ]
            ]);
        }

        // Regular redirect for non-AJAX requests
        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully!');
    }

    /**
     * Display the specified employee.
     */
    public function show(Employee $employee)
    {
        $contracts = $employee->contracts()->orderBy('start_date', 'desc')->get();

        return view('employees.show', compact('employee', 'contracts'));
    }

    /**
     * Show success page after creating employee with option to add contract.
     */
    public function created(Employee $employee)
    {
        // Get data needed for contract form
        $departments = \App\Models\Contract::distinct()->pluck('department')->filter()->sort()->values();
        $workLocations = \App\Models\Contract::distinct()->pluck('work_location')->filter()->sort()->values();

        return view('employees.created', compact('employee', 'departments', 'workLocations'));
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Employee $employee)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can edit employees.');
        }

        return view('employees.edit', compact('employee'));
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can update employees.');
        }

        $validated = $request->validate([
            'employee_name' => 'required|string|max:255',
            'nik' => 'required|string|max:50|unique:employees,nik,' . $employee->id,
            'nid' => 'nullable|string|max:16|unique:employees,nid,' . $employee->id,
            'birthplace' => 'required|string|max:255',
            'birthdate' => 'required|date|before:today',
            'TMT_awal' => 'nullable|date',
            'address' => 'required|string',
            'nomor_hp' => 'nullable|string|max:20',
            'file_cv' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        // Handle file upload
        if ($request->hasFile('file_cv')) {
            // Don't delete old CV - keep it for history
            $file = $request->file('file_cv');
            $filename = time() . '_' . $file->getClientOriginalName();
            $validated['file_cv'] = $file->storeAs('cvs', $filename, 'public');
        }

        $employee->update($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified employee from storage.
     */
    public function destroy(Employee $employee)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can delete employees.');
        }

        // Delete employee (contracts will be cascade deleted)
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}

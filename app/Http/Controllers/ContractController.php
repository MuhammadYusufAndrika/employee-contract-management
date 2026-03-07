<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Employee;
use App\Http\Requests\StoreContractRequest;
use App\Http\Requests\UpdateContractRequest;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Contract::with('employee')
            ->whereIn('id', Contract::latestPerEmployee());

        // Search by employee name or NIK
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('employee', function ($employeeQuery) use ($search) {
                    $employeeQuery->where('employee_name', 'like', '%' . $search . '%')
                        ->orWhere('nik', 'like', '%' . $search . '%');
                })->orWhere('nomor_kontrak', 'like', '%' . $search . '%');
            });
        }

        // Filter by work location
        if ($request->filled('work_location')) {
            $query->where('work_location', $request->work_location);
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        // Filter by status
        if ($request->filled('status')) {
            $today = now();
            switch ($request->status) {
                case 'active':
                    $query->where('status', 'Active')
                        ->where('end_date', '>', $today->copy()->addDays(30));
                    break;
                case 'expiring':
                    $query->where('status', 'Active')
                        ->whereBetween('end_date', [$today, $today->copy()->addDays(30)]);
                    break;
                case 'expired':
                    $query->where('end_date', '<', $today);
                    break;
                case 'permanent':
                    $query->where('status', 'Active')
                        ->whereNull('end_date');
                    break;
                case 'layoff':
                    $query->where('status', 'Layoff');
                    break;
            }
        }

        $contracts = $query->orderBy('end_date', 'desc')->paginate(15)->withQueryString();

        // Get unique values for filters
        $workLocations = Contract::distinct()->pluck('work_location')->sort();
        $departments = Contract::distinct()->pluck('department')->sort();

        return view('contracts.index', compact('contracts', 'workLocations', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can create contracts.');
        }

        $employees = Employee::orderBy('employee_name')->get();
        return view('contracts.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContractRequest $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can create contracts.');
        }

        $data = $request->validated();

        // Handle contract file upload
        if ($request->hasFile('file_contract')) {
            $file = $request->file('file_contract');
            $filename = time() . '_' . $file->getClientOriginalName();
            $data['file_contract'] = $file->storeAs('contracts', $filename, 'public');
        }

        $contract = Contract::create($data);

        // Check if this came from employee creation flow
        if ($request->has('from_employee_creation')) {
            return redirect()->route('employees.show', $contract->employee)
                ->with('success', 'Contract created successfully for ' . $contract->employee->employee_name . '.');
        }

        return redirect()->route('contracts.index')
            ->with('success', 'Contract created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract)
    {
        $contract->load('employee');
        return view('contracts.show', compact('contract'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contract $contract)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can edit contracts.');
        }

        $employees = Employee::orderBy('employee_name')->get();
        return view('contracts.edit', compact('contract', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContractRequest $request, Contract $contract)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can update contracts.');
        }

        $data = $request->validated();

        // Handle contract file upload
        if ($request->hasFile('file_contract')) {
            // Don't delete old file - keep it for history records
            $file = $request->file('file_contract');
            $filename = time() . '_' . $file->getClientOriginalName();
            $data['file_contract'] = $file->storeAs('contracts', $filename, 'public');
        }

        $contract->update($data);

        // Don't create history for updates
        // History is only tracked for renewals

        return redirect()->route('contracts.index')
            ->with('success', 'Contract updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can delete contracts.');
        }

        $contract->delete();

        return redirect()->route('contracts.index')
            ->with('success', 'Contract deleted successfully.');
    }

    /**
     * Display contracts expiring soon.
     */
    public function expiring(Request $request)
    {
        // Get period filter (default to 1 month)
        $period = $request->get('period', '1');

        // Get status filter (expiring or expired)
        $statusFilter = $request->get('status_filter', 'expiring');

        // Calculate days based on period
        $days = match ($period) {
            '1' => 30,      // 1 month
            '3' => 90,      // 3 months
            '6' => 180,     // 6 months
            '12' => 365,    // 1 year
            '12+' => 9999,  // More than 1 year
            default => 30,
        };

        $query = Contract::with('employee')
            ->whereIn('id', Contract::latestPerEmployee())
            ->whereNotNull('end_date')
            ->where('status', '!=', 'Layoff'); // Exclude laid-off employees

        // Filter by status (expiring or expired)
        if ($statusFilter === 'expired') {
            // Show expired contracts
            $query->where('end_date', '<', now());
        } else {
            // Show expiring contracts (default)
            $query->where('end_date', '>', now());

            // Apply period filter for expiring contracts
            if ($period === '12+') {
                $query->where('end_date', '>', now()->addYear());
            } else {
                $query->where('end_date', '<=', now()->addDays($days));
            }
        }

        $contracts = $query->orderBy('end_date', $statusFilter === 'expired' ? 'desc' : 'asc')->get();

        return view('contracts.expiring', compact('contracts', 'period', 'statusFilter'));
    }

    /**
     * Show the form for renewing a contract.
     */
    public function renew(Contract $contract)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can renew contracts.');
        }

        return view('contracts.renew', compact('contract'));
    }

    /**
     * Process contract renewal.
     */
    public function processRenewal(Request $request, Contract $contract)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can process contract renewals.');
        }

        $request->validate([
            'nomor_kontrak' => 'required|string|max:255|unique:contracts,nomor_kontrak,' . $contract->id,
            'job_position' => 'required|string|max:255',
            'point_of_hire' => 'required|string|max:255',
            'contract_type' => 'required|in:Kontrak,KPP',
            'department' => 'required|string|max:255',
            'work_location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required_if:contract_type,Kontrak|nullable|date|after:start_date',
            'file_contract' => 'nullable|file|mimes:pdf|max:5120',
            'notes' => 'nullable|string',
        ]);

        // Create history record for old contract (before updating)
        $contract->createHistory('renewed', 'Contract renewed - ' . ($request->notes ?? 'No additional notes'));

        // Prepare update data
        $data = [
            'nomor_kontrak' => $request->nomor_kontrak,
            'job_position' => $request->job_position,
            'point_of_hire' => $request->point_of_hire,
            'contract_type' => $request->contract_type,
            'department' => $request->department,
            'work_location' => $request->work_location,
            'start_date' => $request->start_date,
            'end_date' => $request->contract_type === 'KPP' ? null : $request->end_date,
        ];

        // Handle file upload
        if ($request->hasFile('file_contract')) {
            // Don't delete old file - keep it for history records
            $file = $request->file('file_contract');
            $filename = time() . '_' . $file->getClientOriginalName();
            $data['file_contract'] = $file->storeAs('contracts', $filename, 'public');
        }

        // Update contract with new data
        $contract->update($data);

        return redirect()->route('contracts.index')
            ->with('success', 'Contract renewed successfully. History has been recorded.');
    }
}

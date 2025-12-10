<?php

namespace App\Http\Controllers;

use App\Models\Contract;
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
        $query = Contract::query();

        // Search by employee name or NIK
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('employee_name', 'like', '%' . $search . '%')
                    ->orWhere('nik', 'like', '%' . $search . '%')
                    ->orWhere('nomor_kontrak', 'like', '%' . $search . '%');
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
                    $query->where('end_date', '>', $today->copy()->addDays(30));
                    break;
                case 'expiring':
                    $query->whereBetween('end_date', [$today, $today->copy()->addDays(30)]);
                    break;
                case 'expired':
                    $query->where('end_date', '<', $today);
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
        return view('contracts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContractRequest $request)
    {
        $data = $request->validated();

        // Handle file upload
        if ($request->hasFile('file_contract')) {
            $file = $request->file('file_contract');
            $filename = time() . '_' . $file->getClientOriginalName();
            $data['file_contract'] = $file->storeAs('contracts', $filename, 'public');
        }

        $contract = Contract::create($data);

        // Don't create history for initial contract creation
        // History is only tracked for renewals

        return redirect()->route('contracts.index')
            ->with('success', 'Contract created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract)
    {
        return view('contracts.show', compact('contract'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contract $contract)
    {
        return view('contracts.edit', compact('contract'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContractRequest $request, Contract $contract)
    {
        $data = $request->validated();

        // Handle file upload
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
        $contract->delete();

        return redirect()->route('contracts.index')
            ->with('success', 'Contract deleted successfully.');
    }

    /**
     * Display contracts expiring soon.
     */
    public function expiring()
    {
        $contracts = Contract::expiringWithinDays(30);
        return view('contracts.expiring', compact('contracts'));
    }

    /**
     * Show the form for renewing a contract.
     */
    public function renew(Contract $contract)
    {
        return view('contracts.renew', compact('contract'));
    }

    /**
     * Process contract renewal.
     */
    public function processRenewal(Request $request, Contract $contract)
    {
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
            'end_date' => $request->end_date,
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

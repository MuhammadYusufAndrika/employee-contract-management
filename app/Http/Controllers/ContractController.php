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
    public function index()
    {
        $contracts = Contract::orderBy('end_date', 'desc')->paginate(15);
        return view('contracts.index', compact('contracts'));
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
        $contract = Contract::create($request->validated());

        // Create history record for new contract
        $contract->createHistory('created', 'Initial contract created');

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
        $contract->update($request->validated());

        // Create history record for update
        $contract->createHistory('updated', 'Contract information updated');

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
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'notes' => 'nullable|string',
        ]);

        // Create history record for old contract
        $contract->createHistory('renewed', 'Contract renewed - ' . ($request->notes ?? 'No additional notes'));

        // Update contract with new dates
        $contract->update([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('contracts.index')
            ->with('success', 'Contract renewed successfully. History has been recorded.');
    }
}

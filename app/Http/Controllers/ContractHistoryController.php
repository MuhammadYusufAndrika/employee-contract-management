<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractHistory;
use Illuminate\Http\Request;

class ContractHistoryController extends Controller
{
    /**
     * Display all contract histories
     */
    public function index()
    {
        $histories = ContractHistory::with('contract')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('contract-histories.index', compact('histories'));
    }

    /**
     * Display history for a specific contract
     */
    public function show(Contract $contract)
    {
        $histories = ContractHistory::where('contract_id', $contract->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('contract-histories.show', compact('contract', 'histories'));
    }

    /**
     * Display history by employee NIK
     */
    public function byNik(Request $request)
    {
        $nik = $request->input('nik');
        
        if (!$nik) {
            return redirect()->route('contract-histories.index')
                ->with('error', 'Please provide a NIK to search.');
        }

        $histories = ContractHistory::where('nik', $nik)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $employee = $histories->first();
        
        return view('contract-histories.by-nik', compact('histories', 'employee', 'nik'));
    }
}

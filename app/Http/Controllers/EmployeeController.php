<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees.
     */
    public function index(Request $request)
    {
        $query = Contract::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('employee_name', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('nomor_kontrak', 'like', "%{$search}%");
            });
        }

        // Department filter
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        // Work location filter
        if ($request->filled('work_location')) {
            $query->where('work_location', $request->work_location);
        }

        $employees = $query->orderBy('employee_name', 'asc')->get();

        // Get unique values for filters
        $workLocations = Contract::distinct()->pluck('work_location')->sort();
        $departments = Contract::distinct()->pluck('department')->sort();

        return view('employees.index', compact('employees', 'workLocations', 'departments'));
    }

    /**
     * Display the specified employee.
     */
    public function show(Contract $employee)
    {
        $histories = $employee->histories()->orderBy('created_at', 'desc')->get();

        return view('employees.show', compact('employee', 'histories'));
    }
}

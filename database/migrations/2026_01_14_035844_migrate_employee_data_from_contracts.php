<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get unique employees from contracts table
        $employees = DB::table('contracts')
            ->select('employee_name', 'nik', 'birthplace', 'birthdate', 'address', 'file_cv')
            ->groupBy('nik', 'employee_name', 'birthplace', 'birthdate', 'address', 'file_cv')
            ->get();

        // Insert into employees table
        foreach ($employees as $employee) {
            DB::table('employees')->insertOrIgnore([
                'employee_name' => $employee->employee_name,
                'nik' => $employee->nik,
                'birthplace' => $employee->birthplace,
                'birthdate' => $employee->birthdate,
                'address' => $employee->address,
                'file_cv' => $employee->file_cv,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Update contracts with employee_id
        DB::statement('
            UPDATE contracts c
            INNER JOIN employees e ON c.nik = e.nik
            SET c.employee_id = e.id
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clear employee_id from contracts
        DB::table('contracts')->update(['employee_id' => null]);

        // Clear employees table
        DB::table('employees')->truncate();
    }
};

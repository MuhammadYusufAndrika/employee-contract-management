<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            // Remove personal information fields (now in employees table)
            $table->dropColumn(['employee_name', 'nik', 'birthplace', 'birthdate', 'address', 'file_cv']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            // Restore personal information fields
            $table->string('employee_name')->after('employee_id');
            $table->string('nik', 16)->after('employee_name');
            $table->string('birthplace')->after('nik');
            $table->date('birthdate')->after('birthplace');
            $table->text('address')->after('birthdate');
            $table->string('file_cv')->nullable()->after('address');
        });
    }
};

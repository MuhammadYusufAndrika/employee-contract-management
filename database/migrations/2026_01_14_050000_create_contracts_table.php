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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('nomor_kontrak')->unique();
            $table->string('job_position');
            $table->string('point_of_hire');
            $table->enum('contract_type', ['Kontrak', 'KPP'])->default('Kontrak');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('department');
            $table->string('work_location');
            $table->string('file_contract')->nullable();
            $table->enum('status', ['Active', 'Terminated', 'Layoff'])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};

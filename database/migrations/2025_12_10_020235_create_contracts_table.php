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
            $table->string('employee_name');
            $table->string('nik')->unique();
            $table->date('birthdate');
            $table->string('birthplace');
            $table->text('address');
            $table->string('job_position');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('department');
            $table->string('work_location');
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

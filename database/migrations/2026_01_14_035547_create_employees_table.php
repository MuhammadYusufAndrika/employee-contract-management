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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_name');
            $table->string('nik', 20)->unique();
            $table->string('nid', 16)->unique()->nullable();
            $table->string('birthplace');
            $table->string('nomor_hp', 20)->nullable();
            $table->date('birthdate');
            $table->date('TMT_awal')->nullable();
            $table->text('address');
            $table->string('file_cv')->nullable();
            $table->enum('status', ['Active', 'Layoff'])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};

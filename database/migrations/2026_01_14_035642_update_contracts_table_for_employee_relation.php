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
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('contracts', 'employee_id')) {
                $table->foreignId('employee_id')->nullable()->after('id')->constrained('employees')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            // Remove foreign key
            $table->dropForeign(['employee_id']);
            $table->dropColumn('employee_id');
        });
    }
};

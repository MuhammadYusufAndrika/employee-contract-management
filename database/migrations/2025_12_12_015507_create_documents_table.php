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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');    
            $table->string('document_number')->nullable();
            $table->string('document_type'); // UUD 1945, Undang-undang, Perppu, etc.
            $table->string('theme')->nullable(); // Kelembagaan Pelatihan, Standar Pelatihan, etc.
            $table->text('description')->nullable();
            $table->date('enacted_date')->nullable(); // Tanggal ditetapkan
            $table->date('published_date')->nullable(); // Tanggal diundangkan
            $table->string('file_path');
            $table->boolean('is_active')->default(true);
            $table->integer('download_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};

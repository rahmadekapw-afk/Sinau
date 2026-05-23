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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->string('kelas');
            $table->string('title');              // "Eksponen dan Logaritma"
            $table->text('description')->nullable(); // Ringkasan singkat
            $table->longText('content');           // Konten lengkap markdown
            $table->integer('chapter_order')->default(1);
            $table->enum('difficulty', ['mudah', 'sedang', 'sulit'])->default('sedang');
            $table->integer('estimated_minutes')->default(30); // Estimasi waktu baca
            $table->timestamps();

            $table->index('kelas');
            $table->index(['subject_id', 'chapter_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};

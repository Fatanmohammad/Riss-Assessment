<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kka_jawabans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kka_id')->constrained('kkas')->cascadeOnDelete();
            $table->foreignId('pertanyaan_id')->constrained('kka_pertanyaans')->cascadeOnDelete();
            $table->text('jawaban')->nullable();
            $table->decimal('nilai', 5, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['kka_id', 'pertanyaan_id'], 'jawaban_unik_per_kka');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kka_jawabans');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kka_pertanyaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bidang_id')->constrained('bidangs')->cascadeOnDelete();
            $table->text('pertanyaan');
            $table->decimal('bobot_nilai', 5, 2)->default(1);
            $table->integer('urutan')->default(0);
            $table->boolean('wajib_diisi')->default(true);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kka_pertanyaans');
    }
};
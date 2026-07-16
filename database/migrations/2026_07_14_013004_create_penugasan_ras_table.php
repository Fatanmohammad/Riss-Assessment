<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penugasan_ras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_plan_id')->constrained('audit_plans')->cascadeOnDelete();
            $table->foreignId('ra_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('cabang_id')->constrained('cabangs')->cascadeOnDelete();
            $table->foreignId('anak_cabang_id')->nullable()->constrained('anak_cabangs')->nullOnDelete();
            $table->foreignId('bidang_id')->constrained('bidangs')->cascadeOnDelete();
            $table->string('periode');                                                         // contoh: 2026-07
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['terjadwal', 'berlangsung', 'selesai', 'batal'])->default('terjadwal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penugasan_ras');
    }
};

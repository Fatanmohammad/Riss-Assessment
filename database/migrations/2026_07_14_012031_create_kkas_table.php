<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kkas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_audit_id')->constrained('jadwal_audits')->cascadeOnDelete();
            $table->foreignId('cabang_id')->constrained('cabangs')->cascadeOnDelete();
            $table->foreignId('bidang_id')->constrained('bidangs')->cascadeOnDelete();
            $table->foreignId('ra_id')->constrained('users')->cascadeOnDelete(); // RA yang mengisi
            $table->enum('status', ['draft', 'submitted', 'direview', 'selesai'])->default('draft');
            $table->timestamp('tanggal_pengisian')->nullable(); // dipakai juga sbg absensi RA
            $table->timestamps();

            $table->unique(['jadwal_audit_id', 'cabang_id', 'bidang_id'], 'kka_unik_per_jadwal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kkas');
    }
};
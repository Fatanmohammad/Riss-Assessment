<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flag_kejanggalans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kka_id')->constrained('kkas')->cascadeOnDelete();
            $table->text('deskripsi');
            $table->enum('tingkat', ['rendah', 'sedang', 'tinggi'])->default('sedang');
            $table->enum('status', ['belum_ditindaklanjuti', 'ditindaklanjuti'])->default('belum_ditindaklanjuti');
            $table->timestamp('terdeteksi_pada');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flag_kejanggalans');
    }
};
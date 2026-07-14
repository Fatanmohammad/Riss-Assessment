<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tindak_lanjuts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flag_id')->constrained('flag_kejanggalans')->cascadeOnDelete();
            $table->foreignId('checker_id')->constrained('users')->cascadeOnDelete();
            $table->text('keterangan');
            $table->string('bukti_fisik')->nullable(); // path file upload
            $table->enum('status', ['proses', 'selesai'])->default('proses');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tindak_lanjuts');
    }
};
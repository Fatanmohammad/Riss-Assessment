<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bidangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_bidang')->unique(); // TELLER, KREDIT, APU_PPT, TEKNOLOGI, dst
            $table->string('nama_bidang'); // Teller, Kredit, APU-PPT, Teknologi/TI
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bidangs');
    }
};
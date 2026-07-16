<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitoring_kinerjas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ra_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('penugasan_id')->constrained('penugasan_ras')->cascadeOnDelete();
            $table->string('periode');                                                          // contoh: 2026-07
            $table->integer('target_kka')->default(0);                                         // dari audit plan
            $table->integer('realisasi_kka')->default(0);                                      // KKA yang sudah selesai
            $table->decimal('persentase_realisasi', 5, 2)->default(0);
            $table->integer('jumlah_temuan')->default(0);
            $table->integer('temuan_signifikan')->default(0);
            $table->integer('temuan_berulang')->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['ra_id', 'penugasan_id', 'periode'], 'monitoring_unik');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitoring_kinerjas');
    }
};

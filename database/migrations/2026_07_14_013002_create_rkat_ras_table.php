<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rkat_ras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();   // RA
            $table->foreignId('cabang_id')->constrained('cabangs')->cascadeOnDelete();
            $table->string('periode');                                                // contoh: 2026
            $table->json('parameter');                                                // parameter rencana kerja (JSON)
            $table->decimal('skor_parameter', 6, 2)->default(0);                     // hasil scoring sistem
            $table->enum('status', ['draft', 'submitted', 'disetujui'])->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'periode'], 'rkat_unik_per_ra_periode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rkat_ras');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scorings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kka_id')->unique()->constrained('kkas')->cascadeOnDelete();
            $table->decimal('total_skor', 6, 2);
            $table->enum('kategori_risiko', ['low', 'medium', 'high']);
            $table->timestamp('dihitung_pada');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scorings');
    }
};
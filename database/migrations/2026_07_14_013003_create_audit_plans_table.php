<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_plans', function (Blueprint $table) {
            $table->id();
            $table->string('periode');                                                        // contoh: 2026
            $table->foreignId('dibuat_oleh')->constrained('users')->cascadeOnDelete();        // PIMSIE/Admin
            $table->text('keterangan')->nullable();
            $table->enum('status', ['draft', 'final'])->default('draft');
            $table->timestamps();

            $table->unique('periode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_plans');
    }
};

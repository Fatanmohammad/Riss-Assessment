<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_trails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('aksi');                                                     // submit, approve, tolak, dll
            $table->string('model');                                                    // nama model yang diubah
            $table->unsignedBigInteger('model_id');                                    // id record yang diubah
            $table->json('data_lama')->nullable();                                      // nilai sebelum diubah
            $table->json('data_baru')->nullable();                                      // nilai sesudah diubah
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['model', 'model_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_trails');
    }
};

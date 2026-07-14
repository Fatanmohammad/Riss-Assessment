<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PertanyaanKka extends Model
{
    use HasFactory;

    protected $table = 'kka_pertanyaans';

    protected $fillable = [
        'bidang_id',
        'pertanyaan',
        'bobot_nilai',
        'urutan',
        'wajib_diisi',
        'aktif',
    ];

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    public function jawaban()
    {
        return $this->hasMany(JawabanKka::class, 'pertanyaan_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    use HasFactory;

    protected $table = 'bidangs';

    protected $fillable = [
        'kode_bidang',
        'nama_bidang',
        'deskripsi',
    ];

    /**
     * Jadwal audit yang menyasar bidang ini (mis. Kredit, Operasional, IT, dsb).
     */
    public function jadwalAudits()
    {
        return $this->hasMany(JadwalAudit::class);
    }

    public function pertanyaanKka()
    {
        return $this->hasMany(PertanyaanKka::class);
    }
}

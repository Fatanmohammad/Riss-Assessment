<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanKka extends Model
{
    use HasFactory;

    protected $table = 'kka_jawabans';

    protected $fillable = [
        'kka_id',
        'pertanyaan_kka_id',
        'auditor_id',
        'jawaban',         // isi jawaban (ya/tidak, teks, skor, dll)
        'skor',
        'lampiran_path',   // bukti/dokumen pendukung
        'keterangan',
    ];

    public function kka()
    {
        return $this->belongsTo(Kka::class);
    }

    public function pertanyaan()
    {
        return $this->belongsTo(PertanyaanKka::class, 'pertanyaan_kka_id');
    }

    public function auditor()
    {
        return $this->belongsTo(User::class, 'auditor_id');
    }
}

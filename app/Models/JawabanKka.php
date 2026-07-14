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
        'pertanyaan_id',
        'jawaban',
        'nilai',
        'keterangan',
    ];

    public function kka()
    {
        return $this->belongsTo(Kka::class);
    }

    public function pertanyaan()
    {
        return $this->belongsTo(PertanyaanKka::class, 'pertanyaan_id');
    }
}

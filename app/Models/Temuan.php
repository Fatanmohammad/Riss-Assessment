<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temuan extends Model
{
    use HasFactory;

    protected $table = 'temuans';

    protected $fillable = [
        'kka_id',
        'auditor_id',
        'judul_temuan',
        'uraian_kondisi',
        'kriteria',
        'sebab',
        'akibat',
        'rekomendasi',
        'tingkat_risiko',   // rendah, sedang, tinggi, sangat_tinggi
        'status',           // terbuka, dalam_tindak_lanjut, selesai
    ];

    public function kka()
    {
        return $this->belongsTo(Kka::class);
    }

    public function auditor()
    {
        return $this->belongsTo(User::class, 'auditor_id');
    }

    public function tindakLanjut()
    {
        return $this->hasMany(TindakLanjut::class);
    }

    public function flagKejanggalan()
    {
        return $this->hasMany(FlagKejanggalan::class);
    }
}

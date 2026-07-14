<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scoring extends Model
{
    use HasFactory;

    protected $table = 'scorings';

    protected $fillable = [
        'kka_id',
        'total_skor',
        'skor_maksimal',
        'persentase',
        'kategori_risiko',   // rendah, sedang, tinggi, sangat_tinggi
        'catatan',
        'dihitung_pada',
    ];

    protected $casts = [
        'dihitung_pada' => 'datetime',
        'total_skor'    => 'decimal:2',
        'skor_maksimal' => 'decimal:2',
        'persentase'    => 'decimal:2',
    ];

    public function kka()
    {
        return $this->belongsTo(Kka::class);
    }
}

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
        'kategori_risiko',   // low, medium, high
        'dihitung_pada',
    ];

    protected $casts = [
        'dihitung_pada' => 'datetime',
        'total_skor'    => 'decimal:2',
    ];

    public function kka()
    {
        return $this->belongsTo(Kka::class);
    }
}

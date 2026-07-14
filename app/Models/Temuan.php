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
        'jenis_temuan',   // signifikan, berulang, minor
        'deskripsi',
        'status',         // baru, berulang, dalam_proses, selesai
    ];

    public function kka()
    {
        return $this->belongsTo(Kka::class);
    }
}

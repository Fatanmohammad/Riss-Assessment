<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlagKejanggalan extends Model
{
    use HasFactory;

    protected $table = 'flag_kejanggalans';

    protected $fillable = [
        'kka_id',
        'deskripsi',
        'tingkat',    // rendah, sedang, tinggi
        'status',     // belum_ditindaklanjuti, ditindaklanjuti
        'terdeteksi_pada',
    ];

    protected $casts = [
        'terdeteksi_pada' => 'datetime',
    ];

    public function kka()
    {
        return $this->belongsTo(Kka::class);
    }

    public function tindakLanjut()
    {
        return $this->hasMany(TindakLanjut::class, 'flag_id');
    }
}

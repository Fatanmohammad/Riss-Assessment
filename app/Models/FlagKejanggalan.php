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
        'temuan_id',
        'jenis_kejanggalan',   // hasil deteksi AnomaliDetectorService
        'deskripsi',
        'tingkat_kepercayaan', // confidence score dari sistem deteksi
        'status',              // baru, diverifikasi, ditolak
        'diverifikasi_oleh',   // FK ke users
        'diverifikasi_pada',
    ];

    protected $casts = [
        'diverifikasi_pada'   => 'datetime',
        'tingkat_kepercayaan' => 'decimal:2',
    ];

    public function kka()
    {
        return $this->belongsTo(Kka::class);
    }

    public function temuan()
    {
        return $this->belongsTo(Temuan::class);
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }
}

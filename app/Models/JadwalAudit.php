<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalAudit extends Model
{
    use HasFactory;

    protected $table = 'jadwal_audits';

    protected $fillable = [
        'cabang_id',
        'bidang_id',
        'periode',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',         // terjadwal, berlangsung, selesai, batal
        'dibuat_oleh',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    public function dibuatOleh()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function kkas()
    {
        return $this->hasMany(Kka::class);
    }

    public function laporan()
    {
        return $this->hasOne(Laporan::class);
    }
}

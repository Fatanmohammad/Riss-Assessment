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
        'ketua_tim_id',   // FK ke users
        'periode_audit',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',         // draft, berjalan, selesai, dibatalkan
        'catatan',
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

    public function ketuaTim()
    {
        return $this->belongsTo(User::class, 'ketua_tim_id');
    }

    /**
     * Anggota tim audit (jika menggunakan tabel pivot jadwal_audit_user).
     */
    public function anggotaTim()
    {
        return $this->belongsToMany(User::class, 'jadwal_audit_user')
            ->withTimestamps();
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

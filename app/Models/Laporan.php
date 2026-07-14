<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporans';

    protected $fillable = [
        'jadwal_audit_id',
        'dibuat_oleh',      // FK ke users
        'judul_laporan',
        'ringkasan_eksekutif',
        'file_path',        // hasil generate PDF/DOCX
        'status',           // draft, final, dikirim
        'tanggal_terbit',
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
    ];

    public function jadwalAudit()
    {
        return $this->belongsTo(JadwalAudit::class);
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}

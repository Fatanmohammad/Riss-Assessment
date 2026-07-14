<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kka extends Model
{
    use HasFactory;

    protected $table = 'kkas';

    protected $fillable = [
        'jadwal_audit_id',
        'cabang_id',
        'bidang_id',
        'ra_id',
        'status',             // draft, submitted, direview, selesai
        'tanggal_pengisian',
    ];

    protected $casts = [
        'tanggal_pengisian' => 'datetime',
    ];

    public function jadwalAudit()
    {
        return $this->belongsTo(JadwalAudit::class);
    }

    public function ra()
    {
        return $this->belongsTo(User::class, 'ra_id');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    public function jawaban()
    {
        return $this->hasMany(JawabanKka::class);
    }

    public function scoring()
    {
        return $this->hasOne(Scoring::class);
    }

    public function temuan()
    {
        return $this->hasMany(Temuan::class);
    }

    public function flagKejanggalan()
    {
        return $this->hasMany(FlagKejanggalan::class);
    }
}

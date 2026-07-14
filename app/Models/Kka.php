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
        'auditor_id',      // FK ke users, pembuat KKA (maker)
        'checker_id',      // FK ke users, pereview (checker)
        'judul',
        'status',          // draft, diajukan, direview, disetujui, ditolak
        'catatan_review',
        'tanggal_pengisian',
        'tanggal_review',
    ];

    protected $casts = [
        'tanggal_pengisian' => 'date',
        'tanggal_review'    => 'date',
    ];

    public function jadwalAudit()
    {
        return $this->belongsTo(JadwalAudit::class);
    }

    public function auditor()
    {
        return $this->belongsTo(User::class, 'auditor_id');
    }

    public function checker()
    {
        return $this->belongsTo(User::class, 'checker_id');
    }

    public function pertanyaan()
    {
        return $this->hasMany(PertanyaanKka::class);
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

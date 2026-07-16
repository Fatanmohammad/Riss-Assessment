<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;

    protected $table = 'cabangs';

    protected $fillable = [
        'kode_cabang',
        'nama_cabang',
        'tipe',       // kcu, biasa
        'slot_ra',    // 1 atau 2
        'alamat',
        'aktif',
    ];

    public function anakCabangs()
    {
        return $this->hasMany(AnakCabang::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function jadwalAudits()
    {
        return $this->hasMany(JadwalAudit::class);
    }

    public function isKcu(): bool
    {
        return $this->tipe === 'kcu';
    }
}

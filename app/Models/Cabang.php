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
        'alamat',
        'kota',
        'status', // aktif / nonaktif
    ];

    /**
     * Semua user (pegawai) yang berada di cabang ini.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Jadwal audit yang dilakukan di cabang ini.
     */
    public function jadwalAudits()
    {
        return $this->hasMany(JadwalAudit::class);
    }
}

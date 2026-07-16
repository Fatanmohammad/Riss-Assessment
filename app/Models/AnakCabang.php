<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnakCabang extends Model
{
    use HasFactory;

    protected $table = 'anak_cabangs';

    protected $fillable = [
        'cabang_id',
        'kode_anak_cabang',
        'nama_anak_cabang',
        'alamat',
        'aktif',
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function kkas()
    {
        return $this->hasMany(Kka::class);
    }

    public function penugasanRas()
    {
        return $this->hasMany(PenugasanRa::class);
    }
}

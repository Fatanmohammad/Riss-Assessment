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
        'tipe',    // induk, anak
        'parent_id',
        'alamat',
        'aktif',
    ];

    public function parent()
    {
        return $this->belongsTo(Cabang::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Cabang::class, 'parent_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function jadwalAudits()
    {
        return $this->hasMany(JadwalAudit::class);
    }
}

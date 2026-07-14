<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'kode_role',
        'nama_role',   // Staf Cabang, Reviewer SKAI, Kepala SKAI, Admin
        'lingkup',     // 'pusat' (SKAI, mereview/mengevaluasi semua cabang) atau 'cabang' (input mandiri)
        'deskripsi',
    ];

    /**
     * User yang memiliki role ini.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * True jika role ini berperan sebagai SKAI Pusat
     * (berwenang mereview/approve KKA, temuan, dan laporan dari semua cabang).
     */
    public function isPusat(): bool
    {
        return $this->lingkup === 'pusat';
    }

    /**
     * True jika role ini berperan sebagai staf cabang (hanya input data cabangnya sendiri).
     */
    public function isCabang(): bool
    {
        return $this->lingkup === 'cabang';
    }
}

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
        'nama_role',
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
        return in_array($this->kode_role, ['CHECKER', 'ADMIN_SKAI']);
    }

    public function isCabang(): bool
    {
        return $this->kode_role === 'RA';
    }
}

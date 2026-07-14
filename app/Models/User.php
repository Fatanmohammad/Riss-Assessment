<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role_id', 'cabang_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Role yang menentukan lingkup akses user (pusat / cabang).
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Cabang tempat user ini bertugas.
     */
    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function jadwalAuditSebagaiKetua()
    {
        return $this->hasMany(JadwalAudit::class, 'ketua_tim_id');
    }

    public function jadwalAuditSebagaiAnggota()
    {
        return $this->belongsToMany(JadwalAudit::class, 'jadwal_audit_user')
            ->withTimestamps();
    }

    public function kkaDibuat()
    {
        return $this->hasMany(Kka::class, 'auditor_id');
    }

    public function kkaDireview()
    {
        return $this->hasMany(Kka::class, 'checker_id');
    }

    /**
     * True jika user ini berperan sebagai SKAI Pusat (lihat Role::isPusat()).
     */
    public function isPusat(): bool
    {
        return $this->role?->isPusat() ?? false;
    }

    /**
     * True jika user ini staf cabang biasa.
     */
    public function isCabang(): bool
    {
        return $this->role?->isCabang() ?? false;
    }
}

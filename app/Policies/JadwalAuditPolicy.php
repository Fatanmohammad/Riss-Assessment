<?php

namespace App\Policies;

use App\Models\JadwalAudit;
use App\Models\User;

class JadwalAuditPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Pusat boleh lihat semua jadwal audit; cabang hanya jadwal untuk cabangnya sendiri.
     */
    public function view(User $user, JadwalAudit $jadwalAudit): bool
    {
        if ($user->isPusat()) {
            return true;
        }

        return $jadwalAudit->cabang_id === $user->cabang_id;
    }

    /**
     * Hanya SKAI Pusat yang menyusun jadwal audit ke cabang-cabang.
     */
    public function create(User $user): bool
    {
        return $user->isPusat();
    }

    public function update(User $user, JadwalAudit $jadwalAudit): bool
    {
        return $user->isPusat();
    }

    public function delete(User $user, JadwalAudit $jadwalAudit): bool
    {
        return $user->isPusat();
    }

    public function restore(User $user, JadwalAudit $jadwalAudit): bool
    {
        return $user->isPusat();
    }

    public function forceDelete(User $user, JadwalAudit $jadwalAudit): bool
    {
        return $user->isPusat();
    }
}

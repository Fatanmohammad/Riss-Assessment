<?php

namespace App\Policies;

use App\Models\Laporan;
use App\Models\User;

class LaporanPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Pusat boleh lihat semua laporan; cabang hanya laporan terkait cabangnya sendiri.
     */
    public function view(User $user, Laporan $laporan): bool
    {
        if ($user->isPusat()) {
            return true;
        }

        return $laporan->cabang_id === $user->cabang_id;
    }

    /**
     * Hanya SKAI Pusat yang menerbitkan laporan resmi hasil audit.
     */
    public function create(User $user): bool
    {
        return $user->isPusat();
    }

    public function update(User $user, Laporan $laporan): bool
    {
        return $user->isPusat();
    }

    public function delete(User $user, Laporan $laporan): bool
    {
        return $user->isPusat();
    }

    public function restore(User $user, Laporan $laporan): bool
    {
        return $user->isPusat();
    }

    public function forceDelete(User $user, Laporan $laporan): bool
    {
        return $user->isPusat();
    }
}

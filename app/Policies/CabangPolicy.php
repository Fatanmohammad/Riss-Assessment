<?php

namespace App\Policies;

use App\Models\Cabang;
use App\Models\User;

class CabangPolicy
{
    /**
     * Semua user login boleh melihat daftar cabang (mis. untuk dropdown, dsb).
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Cabang $cabang): bool
    {
        return true;
    }

    /**
     * Data master cabang (tambah/ubah/hapus) hanya dikelola SKAI Pusat.
     */
    public function create(User $user): bool
    {
        return $user->isPusat();
    }

    public function update(User $user, Cabang $cabang): bool
    {
        return $user->isPusat();
    }

    public function delete(User $user, Cabang $cabang): bool
    {
        return $user->isPusat();
    }

    public function restore(User $user, Cabang $cabang): bool
    {
        return $user->isPusat();
    }

    public function forceDelete(User $user, Cabang $cabang): bool
    {
        return $user->isPusat();
    }
}

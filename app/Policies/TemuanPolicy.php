<?php

namespace App\Policies;

use App\Models\Temuan;
use App\Models\User;

class TemuanPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Pusat boleh lihat semua temuan; cabang hanya temuan dari KKA cabangnya sendiri.
     */
    public function view(User $user, Temuan $temuan): bool
    {
        if ($user->isPusat()) {
            return true;
        }

        $temuan->loadMissing('kka.jadwalAudit');

        return $temuan->kka->jadwalAudit->cabang_id === $user->cabang_id;
    }

    /**
     * Temuan biasanya lahir dari hasil pengisian KKA oleh cabang,
     * jadi staf cabang boleh mencatat temuan awal pada KKA miliknya.
     */
    public function create(User $user): bool
    {
        return $user->isCabang() || $user->isPusat();
    }

    /**
     * Mengubah data dasar temuan (uraian, sebab, akibat) masih boleh oleh
     * pembuatnya (cabang) selama belum dievaluasi Pusat.
     */
    public function update(User $user, Temuan $temuan): bool
    {
        if ($user->isPusat()) {
            return true;
        }

        $temuan->loadMissing('kka');

        return $temuan->kka->ra_id === $user->id && $temuan->status === 'baru';
    }

    public function delete(User $user, Temuan $temuan): bool
    {
        return $user->isPusat();
    }

    public function restore(User $user, Temuan $temuan): bool
    {
        return $user->isPusat();
    }

    public function forceDelete(User $user, Temuan $temuan): bool
    {
        return $user->isPusat();
    }

    /**
     * Evaluasi/verifikasi temuan (tingkat risiko, rekomendasi final, status):
     * KHUSUS wewenang SKAI Pusat.
     */
    public function evaluasi(User $user, Temuan $temuan): bool
    {
        return $user->isPusat();
    }
}

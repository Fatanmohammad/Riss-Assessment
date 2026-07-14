<?php

namespace App\Policies;

use App\Models\Kka;
use App\Models\User;

class KkaPolicy
{
    /**
     * Semua user yang sudah login boleh melihat daftar (query-nya sendiri
     * sudah difilter per cabang di controller/index()).
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Pusat boleh lihat KKA cabang manapun; staf cabang hanya KKA cabangnya sendiri.
     */
    public function view(User $user, Kka $kka): bool
    {
        if ($user->isPusat()) {
            return true;
        }

        $kka->loadMissing('jadwalAudit');

        return $kka->jadwalAudit->cabang_id === $user->cabang_id;
    }

    /**
     * Hanya staf cabang yang membuat KKA (self-assessment). Pusat tidak membuat KKA.
     */
    public function create(User $user): bool
    {
        return $user->isCabang();
    }

    /**
     * Hanya pembuat KKA sendiri, dan hanya selama masih berstatus draft.
     */
    public function update(User $user, Kka $kka): bool
    {
        return $kka->auditor_id === $user->id && $kka->status === 'draft';
    }

    /**
     * Sama seperti update: hanya pembuat, hanya saat draft.
     */
    public function delete(User $user, Kka $kka): bool
    {
        return $this->update($user, $kka);
    }

    public function restore(User $user, Kka $kka): bool
    {
        return $user->isPusat();
    }

    public function forceDelete(User $user, Kka $kka): bool
    {
        return $user->isPusat();
    }

    /**
     * Mengajukan KKA (draft -> diajukan): hanya pembuat KKA, hanya saat draft.
     */
    public function ajukan(User $user, Kka $kka): bool
    {
        return $kka->auditor_id === $user->id && $kka->status === 'draft';
    }

    /**
     * Mereview KKA (approve/tolak): hanya SKAI Pusat, dan hanya saat
     * status sudah diajukan/sedang direview.
     */
    public function review(User $user, Kka $kka): bool
    {
        return $user->isPusat() && in_array($kka->status, ['diajukan', 'direview'], true);
    }
}

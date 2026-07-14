<?php

namespace App\Policies;

use App\Models\TindakLanjut;
use App\Models\User;

class TindakLanjutPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Pusat boleh lihat semua; staf cabang hanya tindak lanjut milik cabangnya,
     * atau yang menjadi penanggung jawab (PIC) langsung.
     */
    public function view(User $user, TindakLanjut $tindakLanjut): bool
    {
        if ($user->isPusat()) {
            return true;
        }

        if ($tindakLanjut->checker_id === $user->id) {
            return true;
        }

        $tindakLanjut->loadMissing('flag.kka.jadwalAudit');

        return $tindakLanjut->flag->kka->jadwalAudit->cabang_id === $user->cabang_id;
    }

    /**
     * Rencana aksi tindak lanjut ditetapkan oleh SKAI Pusat berdasarkan hasil evaluasi temuan.
     */
    public function create(User $user): bool
    {
        return $user->isPusat();
    }

    /**
     * Pusat bisa ubah apa saja (mis. ganti target tanggal/PIC).
     * PIC (staf cabang yang ditunjuk) hanya bisa update realisasi & bukti penyelesaian
     * miliknya sendiri, selama belum berstatus selesai.
     */
    public function update(User $user, TindakLanjut $tindakLanjut): bool
    {
        if ($user->isPusat()) {
            return true;
        }

        return $tindakLanjut->checker_id === $user->id
            && $tindakLanjut->status !== 'selesai';
    }

    /**
     * Hanya Pusat yang boleh menghapus tindak lanjut.
     */
    public function delete(User $user, TindakLanjut $tindakLanjut): bool
    {
        return $user->isPusat();
    }

    public function restore(User $user, TindakLanjut $tindakLanjut): bool
    {
        return $user->isPusat();
    }

    public function forceDelete(User $user, TindakLanjut $tindakLanjut): bool
    {
        return $user->isPusat();
    }
}

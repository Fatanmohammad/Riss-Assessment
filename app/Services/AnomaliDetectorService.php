<?php

namespace App\Services;

use App\Models\FlagKejanggalan;
use App\Models\Kka;
use App\Models\User;

class AnomaliDetectorService
{
    // Ambang lonjakan skor yang dianggap janggal (dalam poin persentase)
    private const AMBANG_LONJAKAN = 30;

    // Batas hari keterlambatan pengisian KKA
    private const BATAS_HARI_TERLAMBAT = 7;

    public function deteksi(Kka $kka): ?FlagKejanggalan
    {
        $kka->loadMissing('scoring', 'ra');

        if (! $kka->scoring) {
            return null;
        }

        // Ambil scoring KKA sebelumnya dari RA yang sama di bidang yang sama
        $skorSebelumnya = Kka::where('ra_id', $kka->ra_id)
            ->where('bidang_id', $kka->bidang_id)
            ->where('id', '<', $kka->id)
            ->whereHas('scoring')
            ->with('scoring')
            ->latest('id')
            ->first()
            ?->scoring
            ?->total_skor;

        if ($skorSebelumnya === null) {
            return null;
        }

        $lonjakan = abs($kka->scoring->total_skor - $skorSebelumnya);

        if ($lonjakan < self::AMBANG_LONJAKAN) {
            return null;
        }

        return FlagKejanggalan::create([
            'kka_id'          => $kka->id,
            'deskripsi'       => "Lonjakan skor sebesar {$lonjakan} poin dari periode sebelumnya ({$skorSebelumnya} → {$kka->scoring->total_skor}).",
            'tingkat'         => $lonjakan >= 50 ? 'tinggi' : 'sedang',
            'status'          => 'belum_ditindaklanjuti',
            'terdeteksi_pada' => now(),
        ]);
    }

    public function cekKeterlambatanPengisian(User $ra): bool
    {
        return Kka::where('ra_id', $ra->id)
            ->where('status', 'draft')
            ->where('tanggal_pengisian', '<=', now()->subDays(self::BATAS_HARI_TERLAMBAT))
            ->exists();
    }
}

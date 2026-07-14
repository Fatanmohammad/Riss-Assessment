<?php

namespace App\Services;

use App\Models\Kka;
use App\Models\Scoring;

class ScoringEngineService
{
    public function hitung(Kka $kka): Scoring
    {
        $kka->loadMissing('jawaban.pertanyaan');

        $totalBobot = $kka->jawaban->sum(fn($j) => $j->pertanyaan?->bobot_nilai ?? 0);
        $totalNilai = $kka->jawaban->sum('nilai');

        $persentase = $totalBobot > 0
            ? round(($totalNilai / $totalBobot) * 100, 2)
            : 0;

        return Scoring::updateOrCreate(
            ['kka_id' => $kka->id],
            [
                'total_skor'      => $persentase,
                'kategori_risiko' => $this->tentukanKategoriRisiko($persentase),
                'dihitung_pada'   => now(),
            ]
        );
    }

    public function tentukanKategoriRisiko(float $persentase): string
    {
        return match (true) {
            $persentase >= 85 => 'rendah',
            $persentase >= 65 => 'sedang',
            $persentase >= 40 => 'tinggi',
            default           => 'sangat_tinggi',
        };
    }
}

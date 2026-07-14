<?php

namespace App\Http\Controllers;

use App\Models\Kka;
use App\Models\Scoring;

class ScoringController extends Controller
{
    public function show(Kka $kka)
    {
        $scoring = $kka->scoring;

        return view('scoring.show', compact('kka', 'scoring'));
    }

    public function hitung(Kka $kka)
    {
        $kka->load('jawaban');

        $totalSkor = $kka->jawaban->sum('nilai');

        Scoring::updateOrCreate(
            ['kka_id' => $kka->id],
            [
                'total_skor'      => $totalSkor,
                'kategori_risiko' => $this->tentukanKategoriRisiko($totalSkor),
                'dihitung_pada'   => now(),
            ]
        );

        return redirect()
            ->route('kka.show', $kka)
            ->with('success', 'Scoring berhasil dihitung.');
    }

    private function tentukanKategoriRisiko(float $totalSkor): string
    {
        return match (true) {
            $totalSkor >= 85 => 'low',
            $totalSkor >= 65 => 'medium',
            default          => 'high',
        };
    }
}

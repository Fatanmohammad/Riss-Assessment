<?php

namespace App\Http\Controllers;

use App\Models\Kka;
use App\Models\Scoring;
use Illuminate\Http\Request;

class ScoringController extends Controller
{
    /**
     * Tampilkan detail scoring untuk satu KKA.
     */
    public function show(Kka $kka)
    {
        $scoring = $kka->scoring;

        return view('scoring.show', compact('kka', 'scoring'));
    }

    /**
     * Hitung ulang scoring untuk sebuah KKA.
     *
     * Catatan: perhitungan skor sesungguhnya akan didelegasikan ke
     * App\Services\ScoringEngineService (belum dibuat sesuai permintaan).
     * Untuk sementara method ini hanya menyiapkan alur controller-nya.
     */
    public function hitung(Request $request, Kka $kka)
    {
        $kka->load('jawaban.pertanyaan');

        // TODO: ganti dengan pemanggilan ScoringEngineService
        // $hasil = app(\App\Services\ScoringEngineService::class)->hitung($kka);

        $totalSkor    = $kka->jawaban->sum('skor');
        $skorMaksimal = $kka->pertanyaan->sum('bobot');
        $persentase   = $skorMaksimal > 0 ? round(($totalSkor / $skorMaksimal) * 100, 2) : 0;

        $scoring = Scoring::updateOrCreate(
            ['kka_id' => $kka->id],
            [
                'total_skor'     => $totalSkor,
                'skor_maksimal'  => $skorMaksimal,
                'persentase'     => $persentase,
                'kategori_risiko'=> $this->tentukanKategoriRisiko($persentase),
                'dihitung_pada'  => now(),
            ]
        );

        return redirect()
            ->route('kka.show', $kka)
            ->with('success', 'Scoring berhasil dihitung ulang.');
    }

    private function tentukanKategoriRisiko(float $persentase): string
    {
        return match (true) {
            $persentase >= 85 => 'rendah',
            $persentase >= 65 => 'sedang',
            $persentase >= 40 => 'tinggi',
            default            => 'sangat_tinggi',
        };
    }
}

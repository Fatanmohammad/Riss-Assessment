<?php

namespace App\Services;

use App\Models\Cabang;
use App\Models\Kka;
use App\Models\Laporan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class LaporanGeneratorService
{
    public function generateLaporanBulanan(User $ra, string $periode): Laporan
    {
        $kkas = Kka::where('ra_id', $ra->id)
            ->where('status', 'selesai')
            ->whereLike('tanggal_pengisian', "{$periode}%")
            ->with(['scoring', 'temuan', 'bidang'])
            ->get();

        $pdf = Pdf::loadView('laporan.bulanan', [
            'ra'     => $ra,
            'periode' => $periode,
            'kkas'   => $kkas,
        ]);

        $path = "laporans/bulanan/{$ra->id}_{$periode}.pdf";
        Storage::put($path, $pdf->output());

        return Laporan::updateOrCreate(
            ['jenis' => 'bulanan', 'periode' => $periode, 'ra_id' => $ra->id],
            ['file_path' => $path, 'cabang_id' => $ra->cabang_id, 'dibuat_oleh' => $ra->id]
        );
    }

    public function generateEvaluasiUnit(Cabang $cabang, string $periode): Laporan
    {
        $kkas = Kka::where('cabang_id', $cabang->id)
            ->where('status', 'selesai')
            ->whereLike('tanggal_pengisian', "{$periode}%")
            ->with(['scoring', 'temuan', 'ra', 'bidang'])
            ->get();

        $pdf = Pdf::loadView('laporan.evaluasi_unit', [
            'cabang'  => $cabang,
            'periode' => $periode,
            'kkas'    => $kkas,
        ]);

        $path = "laporans/evaluasi/{$cabang->id}_{$periode}.pdf";
        Storage::put($path, $pdf->output());

        return Laporan::updateOrCreate(
            ['jenis' => 'triwulan', 'periode' => $periode, 'cabang_id' => $cabang->id, 'ra_id' => null],
            ['file_path' => $path, 'dibuat_oleh' => auth()->id()]
        );
    }
}

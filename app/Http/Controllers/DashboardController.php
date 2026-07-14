<?php

namespace App\Http\Controllers;

use App\Models\FlagKejanggalan;
use App\Models\JadwalAudit;
use App\Models\Kka;
use App\Models\Temuan;
use App\Models\TindakLanjut;

class DashboardController extends Controller
{
    public function index()
    {
        $summary = [
            'jadwal_berjalan'     => JadwalAudit::where('status', 'berjalan')->count(),
            'kka_menunggu_review' => Kka::where('status', 'diajukan')->count(),
            'temuan_terbuka'      => Temuan::where('status', 'terbuka')->count(),
            'tindak_lanjut_lewat' => TindakLanjut::where('status', '!=', 'selesai')
                ->where('target_tanggal', '<', now())
                ->count(),
            'flag_baru'           => FlagKejanggalan::where('status', 'baru')->count(),
        ];

        $temuanTerbaru = Temuan::with(['kka.jadwalAudit.cabang'])
            ->latest()
            ->take(5)
            ->get();

        $jadwalMendatang = JadwalAudit::with(['cabang', 'bidang'])
            ->where('tanggal_mulai', '>=', now())
            ->orderBy('tanggal_mulai')
            ->take(5)
            ->get();

        return view('dashboard.index', compact('summary', 'temuanTerbaru', 'jadwalMendatang'));
    }
}

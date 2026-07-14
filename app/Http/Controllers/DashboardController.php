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
            'jadwal_berjalan'     => JadwalAudit::where('status', 'berlangsung')->count(),
            'kka_menunggu_review' => Kka::where('status', 'submitted')->count(),
            'temuan_baru'         => Temuan::where('status', 'baru')->count(),
            'flag_belum_ditindak' => FlagKejanggalan::where('status', 'belum_ditindaklanjuti')->count(),
            'tindak_lanjut_proses'=> TindakLanjut::where('status', 'proses')->count(),
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

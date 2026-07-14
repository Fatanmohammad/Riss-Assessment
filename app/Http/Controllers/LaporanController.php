<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLaporanRequest;
use App\Models\JadwalAudit;
use App\Models\Laporan;
use Illuminate\Support\Facades\Auth;

/**
 * Hanya SKAI Pusat yang berwenang menerbitkan Laporan hasil audit.
 * Staf cabang hanya bisa melihat laporan terkait cabangnya sendiri.
 */
class LaporanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Laporan::with(['jadwalAudit.cabang', 'pembuat']);

        if ($user->isCabang()) {
            $query->whereHas('jadwalAudit', function ($q) use ($user) {
                $q->where('cabang_id', $user->cabang_id);
            });
        }

        $laporans = $query->latest()->paginate(15);

        return view('laporan.index', compact('laporans'));
    }

    public function create()
    {
        $this->pastikanPusat();

        $jadwalAudits = JadwalAudit::where('status', 'selesai')
            ->whereDoesntHave('laporan')
            ->get();

        return view('laporan.create', compact('jadwalAudits'));
    }

    public function store(StoreLaporanRequest $request)
    {
        $this->pastikanPusat();

        $validated = $request->validated();
        $validated['dibuat_oleh'] = Auth::id();

        $laporan = Laporan::create($validated + ['status' => $validated['status'] ?? 'draft']);

        // Catatan: pembuatan file PDF/DOCX otomatis akan didelegasikan ke
        // App\Services\LaporanGeneratorService (belum dibuat sesuai permintaan)
        // atau dijalankan lewat App\Jobs\GenerateLaporanJob secara async.

        return redirect()
            ->route('laporan.show', $laporan)
            ->with('success', 'Laporan berhasil dibuat.');
    }

    public function show(Laporan $laporan)
    {
        $user = Auth::user();

        if ($user->isCabang()) {
            $laporan->loadMissing('jadwalAudit');

            if ($laporan->jadwalAudit->cabang_id !== $user->cabang_id) {
                abort(403, 'Anda tidak memiliki akses ke laporan cabang lain.');
            }
        }

        $laporan->load(['jadwalAudit.cabang', 'jadwalAudit.bidang', 'pembuat']);

        return view('laporan.show', compact('laporan'));
    }

    public function edit(Laporan $laporan)
    {
        $this->pastikanPusat();

        return view('laporan.edit', compact('laporan'));
    }

    public function update(StoreLaporanRequest $request, Laporan $laporan)
    {
        $this->pastikanPusat();

        $validated = $request->validated();
        unset($validated['dibuat_oleh']);

        $laporan->update($validated);

        return redirect()
            ->route('laporan.show', $laporan)
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy(Laporan $laporan)
    {
        $this->pastikanPusat();

        $laporan->delete();

        return redirect()
            ->route('laporan.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }

    private function pastikanPusat(): void
    {
        if (! Auth::user()->isPusat()) {
            abort(403, 'Hanya SKAI Pusat yang berwenang mengelola laporan.');
        }
    }
}

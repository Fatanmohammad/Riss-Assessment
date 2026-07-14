<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLaporanRequest;
use App\Models\Cabang;
use App\Models\Laporan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $query = Laporan::with(['cabang', 'pembuat']);

        if ($user->isCabang()) {
            $query->where('cabang_id', $user->cabang_id);
        }

        $laporans = $query->latest()->paginate(15);

        return view('laporan.index', compact('laporans'));
    }

    public function create()
    {
        $this->pastikanPusat();

        $cabangs = Cabang::where('aktif', true)->orderBy('nama_cabang')->get();
        $ras     = User::whereHas('role', fn($q) => $q->where('kode_role', 'RA'))->get();

        return view('laporan.create', compact('cabangs', 'ras'));
    }

    public function store(StoreLaporanRequest $request)
    {
        $this->pastikanPusat();

        $validated                = $request->validated();
        $validated['dibuat_oleh'] = Auth::id();

        $laporan = Laporan::create($validated);

        return redirect()
            ->route('laporan.show', $laporan)
            ->with('success', 'Laporan berhasil dibuat.');
    }

    public function show(Laporan $laporan)
    {
        $user = Auth::user();

        if ($user->isCabang() && $laporan->cabang_id !== $user->cabang_id) {
            abort(403, 'Anda tidak memiliki akses ke laporan cabang lain.');
        }

        $laporan->load(['cabang', 'ra', 'pembuat']);

        return view('laporan.show', compact('laporan'));
    }

    public function edit(Laporan $laporan)
    {
        $this->pastikanPusat();

        $cabangs = Cabang::orderBy('nama_cabang')->get();
        $ras     = User::whereHas('role', fn($q) => $q->where('kode_role', 'RA'))->get();

        return view('laporan.edit', compact('laporan', 'cabangs', 'ras'));
    }

    public function update(StoreLaporanRequest $request, Laporan $laporan)
    {
        $this->pastikanPusat();

        $laporan->update($request->validated());

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

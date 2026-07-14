<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJadwalAuditRequest;
use App\Models\Bidang;
use App\Models\Cabang;
use App\Models\JadwalAudit;
use Illuminate\Support\Facades\Auth;

class JadwalAuditController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $query = JadwalAudit::with(['cabang', 'bidang', 'dibuatOleh']);

        if ($user->isCabang()) {
            $query->where('cabang_id', $user->cabang_id);
        }

        $jadwalAudits = $query->latest()->paginate(15);

        return view('jadwal-audit.index', compact('jadwalAudits'));
    }

    public function create()
    {
        $this->pastikanPusat();

        $cabangs = Cabang::where('aktif', true)->orderBy('nama_cabang')->get();
        $bidangs = Bidang::orderBy('nama_bidang')->get();

        return view('jadwal-audit.create', compact('cabangs', 'bidangs'));
    }

    public function store(StoreJadwalAuditRequest $request)
    {
        $this->pastikanPusat();

        $validated                = $request->validated();
        $validated['dibuat_oleh'] = Auth::id();

        $jadwalAudit = JadwalAudit::create($validated);

        return redirect()
            ->route('jadwal-audit.show', $jadwalAudit)
            ->with('success', 'Jadwal audit berhasil dibuat.');
    }

    public function show(JadwalAudit $jadwalAudit)
    {
        $jadwalAudit->load(['cabang', 'bidang', 'dibuatOleh', 'kkas']);

        return view('jadwal-audit.show', compact('jadwalAudit'));
    }

    public function edit(JadwalAudit $jadwalAudit)
    {
        $this->pastikanPusat();

        $cabangs = Cabang::orderBy('nama_cabang')->get();
        $bidangs = Bidang::orderBy('nama_bidang')->get();

        return view('jadwal-audit.edit', compact('jadwalAudit', 'cabangs', 'bidangs'));
    }

    public function update(StoreJadwalAuditRequest $request, JadwalAudit $jadwalAudit)
    {
        $this->pastikanPusat();

        $jadwalAudit->update($request->validated());

        return redirect()
            ->route('jadwal-audit.show', $jadwalAudit)
            ->with('success', 'Jadwal audit berhasil diperbarui.');
    }

    public function destroy(JadwalAudit $jadwalAudit)
    {
        $this->pastikanPusat();

        $jadwalAudit->delete();

        return redirect()
            ->route('jadwal-audit.index')
            ->with('success', 'Jadwal audit berhasil dihapus.');
    }

    private function pastikanPusat(): void
    {
        if (! Auth::user()->isPusat()) {
            abort(403, 'Hanya SKAI Pusat yang berwenang mengelola jadwal audit.');
        }
    }
}

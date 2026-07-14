<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJadwalAuditRequest;
use App\Models\Bidang;
use App\Models\Cabang;
use App\Models\JadwalAudit;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class JadwalAuditController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = JadwalAudit::with(['cabang', 'bidang', 'ketuaTim']);

        // Staf cabang hanya melihat jadwal audit untuk cabangnya sendiri.
        if ($user->isCabang()) {
            $query->where('cabang_id', $user->cabang_id);
        }

        $jadwalAudits = $query->latest()->paginate(15);

        return view('jadwal-audit.index', compact('jadwalAudits'));
    }

    public function create()
    {
        $this->pastikanPusat();

        $cabangs = Cabang::where('status', 'aktif')->orderBy('nama_cabang')->get();
        $bidangs = Bidang::orderBy('nama_bidang')->get();
        $users   = User::orderBy('name')->get();

        return view('jadwal-audit.create', compact('cabangs', 'bidangs', 'users'));
    }

    public function store(StoreJadwalAuditRequest $request)
    {
        $this->pastikanPusat();

        $validated = $request->validated();
        $anggotaTim = $validated['anggota_tim'] ?? [];
        unset($validated['anggota_tim']);

        $jadwalAudit = JadwalAudit::create($validated);

        if (!empty($anggotaTim)) {
            $jadwalAudit->anggotaTim()->sync($anggotaTim);
        }

        return redirect()
            ->route('jadwal-audit.index')
            ->with('success', 'Jadwal audit berhasil dibuat.');
    }

    public function show(JadwalAudit $jadwalAudit)
    {
        $jadwalAudit->load(['cabang', 'bidang', 'ketuaTim', 'anggotaTim', 'kkas', 'laporan']);

        return view('jadwal-audit.show', compact('jadwalAudit'));
    }

    public function edit(JadwalAudit $jadwalAudit)
    {
        $this->pastikanPusat();

        $cabangs = Cabang::orderBy('nama_cabang')->get();
        $bidangs = Bidang::orderBy('nama_bidang')->get();
        $users   = User::orderBy('name')->get();

        $jadwalAudit->load('anggotaTim');

        return view('jadwal-audit.edit', compact('jadwalAudit', 'cabangs', 'bidangs', 'users'));
    }

    public function update(StoreJadwalAuditRequest $request, JadwalAudit $jadwalAudit)
    {
        $this->pastikanPusat();

        $validated = $request->validated();
        $anggotaTim = $validated['anggota_tim'] ?? [];
        unset($validated['anggota_tim']);

        $jadwalAudit->update($validated);
        $jadwalAudit->anggotaTim()->sync($anggotaTim);

        return redirect()
            ->route('jadwal-audit.index')
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

    /**
     * Hanya SKAI Pusat yang boleh membuat/mengubah/menghapus jadwal audit;
     * staf cabang hanya menerima jadwal yang sudah ditentukan Pusat.
     */
    private function pastikanPusat(): void
    {
        if (! Auth::user()->isPusat()) {
            abort(403, 'Hanya SKAI Pusat yang berwenang mengelola jadwal audit.');
        }
    }
}

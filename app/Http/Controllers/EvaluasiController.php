<?php

namespace App\Http\Controllers;

use App\Models\Temuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Mengelola alur evaluasi/verifikasi temuan hasil audit.
 * Hanya SKAI Pusat yang berwenang melakukan evaluasi/verifikasi temuan dari semua cabang.
 * Staf cabang hanya bisa melihat (index/show) temuan milik cabangnya sendiri.
 */
class EvaluasiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Temuan::with(['kka.jadwalAudit.cabang', 'auditor']);

        if ($user->isCabang()) {
            $query->whereHas('kka.jadwalAudit', function ($q) use ($user) {
                $q->where('cabang_id', $user->cabang_id);
            });
        }

        $temuans = $query->latest()->paginate(15);

        return view('evaluasi.index', compact('temuans'));
    }

    public function create()
    {
        $this->pastikanPusat();

        $temuans = Temuan::where('status', 'terbuka')->get();

        return view('evaluasi.create', compact('temuans'));
    }

    public function store(Request $request)
    {
        $this->pastikanPusat();

        $validated = $request->validate([
            'temuan_id'       => ['required', 'exists:temuans,id'],
            'tingkat_risiko'  => ['required', 'in:rendah,sedang,tinggi,sangat_tinggi'],
            'rekomendasi'     => ['nullable', 'string'],
            'status'          => ['required', 'in:terbuka,dalam_tindak_lanjut,selesai'],
        ]);

        $temuan = Temuan::findOrFail($validated['temuan_id']);
        $temuan->update([
            'tingkat_risiko' => $validated['tingkat_risiko'],
            'rekomendasi'    => $validated['rekomendasi'] ?? $temuan->rekomendasi,
            'status'         => $validated['status'],
        ]);

        return redirect()
            ->route('evaluasi.show', $temuan)
            ->with('success', 'Evaluasi temuan berhasil disimpan.');
    }

    public function show(Temuan $evaluasi)
    {
        $user = Auth::user();

        if ($user->isCabang()) {
            $evaluasi->loadMissing('kka.jadwalAudit');

            if ($evaluasi->kka->jadwalAudit->cabang_id !== $user->cabang_id) {
                abort(403, 'Anda tidak memiliki akses ke temuan cabang lain.');
            }
        }

        $evaluasi->load(['kka.jadwalAudit.cabang', 'auditor', 'tindakLanjut', 'flagKejanggalan']);

        return view('evaluasi.show', ['temuan' => $evaluasi]);
    }

    public function edit(Temuan $evaluasi)
    {
        $this->pastikanPusat();

        return view('evaluasi.edit', ['temuan' => $evaluasi]);
    }

    public function update(Request $request, Temuan $evaluasi)
    {
        $this->pastikanPusat();

        $validated = $request->validate([
            'tingkat_risiko'  => ['required', 'in:rendah,sedang,tinggi,sangat_tinggi'],
            'rekomendasi'     => ['nullable', 'string'],
            'status'          => ['required', 'in:terbuka,dalam_tindak_lanjut,selesai'],
        ]);

        $evaluasi->update($validated);

        return redirect()
            ->route('evaluasi.show', $evaluasi)
            ->with('success', 'Evaluasi temuan berhasil diperbarui.');
    }

    public function destroy(Temuan $evaluasi)
    {
        $this->pastikanPusat();

        $evaluasi->delete();

        return redirect()
            ->route('evaluasi.index')
            ->with('success', 'Evaluasi temuan berhasil dihapus.');
    }

    private function pastikanPusat(): void
    {
        if (! Auth::user()->isPusat()) {
            abort(403, 'Hanya SKAI Pusat yang berwenang mengevaluasi temuan.');
        }
    }
}

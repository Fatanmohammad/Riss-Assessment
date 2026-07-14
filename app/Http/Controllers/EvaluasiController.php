<?php

namespace App\Http\Controllers;

use App\Models\Temuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluasiController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $query = Temuan::with(['kka.jadwalAudit.cabang']);

        if ($user->isCabang()) {
            $query->whereHas('kka.jadwalAudit', function ($q) use ($user) {
                $q->where('cabang_id', $user->cabang_id);
            });
        }

        $temuans = $query->latest()->paginate(15);

        return view('evaluasi.index', compact('temuans'));
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

        $evaluasi->load(['kka.jadwalAudit.cabang']);

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
            'jenis_temuan' => ['required', 'in:signifikan,berulang,minor'],
            'deskripsi'    => ['required', 'string'],
            'status'       => ['required', 'in:baru,berulang,dalam_proses,selesai'],
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
            ->with('success', 'Temuan berhasil dihapus.');
    }

    private function pastikanPusat(): void
    {
        if (! Auth::user()->isPusat()) {
            abort(403, 'Hanya SKAI Pusat yang berwenang mengevaluasi temuan.');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Kka;
use App\Models\Temuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemuanController extends Controller
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

        return view('temuan.index', compact('temuans'));
    }

    public function create()
    {
        $user = Auth::user();

        $kkas = Kka::when($user->isCabang(), function ($q) use ($user) {
            $q->whereHas('jadwalAudit', fn($q) => $q->where('cabang_id', $user->cabang_id));
        })->get();

        return view('temuan.create', compact('kkas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kka_id'       => ['required', 'exists:kkas,id'],
            'jenis_temuan' => ['required', 'in:signifikan,berulang,minor'],
            'deskripsi'    => ['required', 'string'],
            'status'       => ['nullable', 'in:baru,berulang,dalam_proses,selesai'],
        ]);

        $validated['status'] = $validated['status'] ?? 'baru';

        $temuan = Temuan::create($validated);

        return redirect()
            ->route('temuan.show', $temuan)
            ->with('success', 'Temuan berhasil dicatat.');
    }

    public function show(Temuan $temuan)
    {
        $user = Auth::user();

        if ($user->isCabang()) {
            $temuan->loadMissing('kka.jadwalAudit');

            if ($temuan->kka->jadwalAudit->cabang_id !== $user->cabang_id) {
                abort(403, 'Anda tidak memiliki akses ke temuan cabang lain.');
            }
        }

        $temuan->load(['kka.jadwalAudit.cabang']);

        return view('temuan.show', compact('temuan'));
    }

    public function edit(Temuan $temuan)
    {
        $user = Auth::user();

        $temuan->loadMissing('kka');

        if ($user->isCabang() && $temuan->kka->ra_id !== $user->id) {
            abort(403, 'Anda tidak berwenang mengedit temuan ini.');
        }

        return view('temuan.edit', compact('temuan'));
    }

    public function update(Request $request, Temuan $temuan)
    {
        $user = Auth::user();

        $temuan->loadMissing('kka');

        if ($user->isCabang() && $temuan->kka->ra_id !== $user->id) {
            abort(403, 'Anda tidak berwenang mengubah temuan ini.');
        }

        $validated = $request->validate([
            'jenis_temuan' => ['required', 'in:signifikan,berulang,minor'],
            'deskripsi'    => ['required', 'string'],
            'status'       => ['required', 'in:baru,berulang,dalam_proses,selesai'],
        ]);

        $temuan->update($validated);

        return redirect()
            ->route('temuan.show', $temuan)
            ->with('success', 'Temuan berhasil diperbarui.');
    }

    public function destroy(Temuan $temuan)
    {
        if (! Auth::user()->isPusat()) {
            abort(403, 'Hanya SKAI Pusat yang berwenang menghapus temuan.');
        }

        $temuan->delete();

        return redirect()
            ->route('temuan.index')
            ->with('success', 'Temuan berhasil dihapus.');
    }
}

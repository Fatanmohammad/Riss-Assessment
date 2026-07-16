<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTindakLanjutRequest;
use App\Models\FlagKejanggalan;
use App\Models\TindakLanjut;
use Illuminate\Support\Facades\Auth;

class TindakLanjutController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $query = TindakLanjut::with(['flag.kka.jadwalAudit.cabang', 'checker']);

        if ($user->isCabang()) {
            $query->where(function ($q) use ($user) {
                $q->where('checker_id', $user->id)
                    ->orWhereHas('flag.kka.jadwalAudit', function ($q) use ($user) {
                        $q->where('cabang_id', $user->cabang_id);
                    });
            });
        }

        $tindakLanjuts = $query->latest()->paginate(15);

        return view('tindak-lanjut.index', compact('tindakLanjuts'));
    }

    public function create()
    {
        $this->pastikanPusat();

        $flags = FlagKejanggalan::where('status', 'belum_ditindaklanjuti')
            ->with('kka.jadwalAudit.cabang')
            ->get();

        return view('tindak-lanjut.create', compact('flags'));
    }

    public function store(StoreTindakLanjutRequest $request)
    {
        $this->pastikanPusat();

        $validated = $request->validated();

        if ($request->hasFile('bukti_fisik')) {
            $validated['bukti_fisik'] = $request->file('bukti_fisik')
                ->store('bukti-tindak-lanjut', 'public');
        }

        $tindakLanjut = TindakLanjut::create($validated);

        FlagKejanggalan::find($validated['flag_id'])
            ->update(['status' => 'ditindaklanjuti']);

        return redirect()
            ->route('tindak-lanjut.show', $tindakLanjut)
            ->with('success', 'Tindak lanjut berhasil dicatat.');
    }

    public function show(TindakLanjut $tindakLanjut)
    {
        $user = Auth::user();

        if ($user->isCabang()) {
            $tindakLanjut->loadMissing('flag.kka.jadwalAudit');

            $cabangId = $tindakLanjut->flag->kka->jadwalAudit->cabang_id;

            if ($tindakLanjut->checker_id !== $user->id && $cabangId !== $user->cabang_id) {
                abort(403, 'Anda tidak memiliki akses ke tindak lanjut ini.');
            }
        }

        $tindakLanjut->load(['flag.kka.jadwalAudit.cabang', 'checker']);

        return view('tindak-lanjut.show', compact('tindakLanjut'));
    }

    public function edit(TindakLanjut $tindakLanjut)
    {
        $user = Auth::user();

        if ($user->isCabang() && $tindakLanjut->checker_id !== $user->id) {
            abort(403, 'Anda tidak berwenang mengedit tindak lanjut ini.');
        }

        if ($tindakLanjut->status === 'selesai') {
            abort(403, 'Tindak lanjut yang sudah selesai tidak bisa diubah.');
        }

        return view('tindak-lanjut.edit', compact('tindakLanjut'));
    }

    public function update(StoreTindakLanjutRequest $request, TindakLanjut $tindakLanjut)
    {
        $user = Auth::user();

        if ($user->isCabang() && $tindakLanjut->checker_id !== $user->id) {
            abort(403, 'Anda tidak berwenang mengubah tindak lanjut ini.');
        }

        $validated = $request->validated();

        if ($request->hasFile('bukti_fisik')) {
            $validated['bukti_fisik'] = $request->file('bukti_fisik')
                ->store('bukti-tindak-lanjut', 'public');
        }

        $tindakLanjut->update($validated);

        return redirect()
            ->route('tindak-lanjut.show', $tindakLanjut)
            ->with('success', 'Tindak lanjut berhasil diperbarui.');
    }

    public function destroy(TindakLanjut $tindakLanjut)
    {
        $this->pastikanPusat();

        $tindakLanjut->delete();

        return redirect()
            ->route('tindak-lanjut.index')
            ->with('success', 'Tindak lanjut berhasil dihapus.');
    }

    private function pastikanPusat(): void
    {
        if (! Auth::user()->isPusat()) {
            abort(403, 'Hanya SKAI Pusat yang berwenang mengelola tindak lanjut.');
        }
    }
}

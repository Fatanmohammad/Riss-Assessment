<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKkaRequest;
use App\Models\JadwalAudit;
use App\Models\JawabanKka;
use App\Models\Kka;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KkaController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $query = Kka::with(['jadwalAudit.cabang', 'ra']);

        if ($user->isCabang()) {
            $query->whereHas('jadwalAudit', function ($q) use ($user) {
                $q->where('cabang_id', $user->cabang_id);
            });
        }

        $kkas = $query->latest()->paginate(15);

        return view('kka.index', compact('kkas'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->isPusat()) {
            abort(403, 'SKAI Pusat tidak membuat KKA.');
        }

        $jadwalAudits = JadwalAudit::where('status', 'berlangsung')
            ->where('cabang_id', $user->cabang_id)
            ->get();

        return view('kka.create', compact('jadwalAudits'));
    }

    public function store(StoreKkaRequest $request)
    {
        $user = Auth::user();

        if ($user->isPusat()) {
            abort(403, 'SKAI Pusat tidak membuat KKA.');
        }

        $validated = $request->validated();

        $jadwalAudit = JadwalAudit::findOrFail($validated['jadwal_audit_id']);

        if ($jadwalAudit->cabang_id !== $user->cabang_id) {
            abort(403, 'Anda hanya bisa mengisi KKA untuk cabang anda sendiri.');
        }

        $jawabanList = $validated['jawaban'] ?? [];
        unset($validated['jawaban']);

        $validated['ra_id']     = $user->id;
        $validated['cabang_id'] = $jadwalAudit->cabang_id;
        $validated['bidang_id'] = $jadwalAudit->bidang_id;
        $validated['status']    = 'draft';

        $kka = DB::transaction(function () use ($validated, $jawabanList) {
            $kka = Kka::create($validated);

            foreach ($jawabanList as $item) {
                JawabanKka::create([
                    'kka_id'         => $kka->id,
                    'pertanyaan_id'  => $item['pertanyaan_id'],
                    'jawaban'        => $item['jawaban'],
                    'nilai'          => $item['nilai'] ?? 0,
                    'keterangan'     => $item['keterangan'] ?? null,
                ]);
            }

            return $kka;
        });

        return redirect()
            ->route('kka.show', $kka)
            ->with('success', 'KKA berhasil disimpan sebagai draft.');
    }

    public function show(Kka $kka)
    {
        $this->pastikanBolehAkses($kka);

        $kka->load(['jadwalAudit.cabang', 'ra', 'jawaban.pertanyaan', 'scoring', 'temuan']);

        return view('kka.show', compact('kka'));
    }

    public function edit(Kka $kka)
    {
        $user = Auth::user();

        if ($kka->ra_id !== $user->id || $kka->status !== 'draft') {
            abort(403, 'KKA hanya bisa diedit oleh pembuatnya selama masih berstatus draft.');
        }

        $kka->load(['jawaban.pertanyaan']);

        return view('kka.edit', compact('kka'));
    }

    public function update(StoreKkaRequest $request, Kka $kka)
    {
        $user = Auth::user();

        if ($kka->ra_id !== $user->id || $kka->status !== 'draft') {
            abort(403, 'KKA hanya bisa diubah oleh pembuatnya selama masih berstatus draft.');
        }

        $validated = $request->validated();
        unset($validated['jawaban'], $validated['ra_id'], $validated['status']);

        $kka->update($validated);

        return redirect()
            ->route('kka.show', $kka)
            ->with('success', 'KKA berhasil diperbarui.');
    }

    public function destroy(Kka $kka)
    {
        $user = Auth::user();

        if ($kka->ra_id !== $user->id || $kka->status !== 'draft') {
            abort(403, 'KKA hanya bisa dihapus oleh pembuatnya selama masih berstatus draft.');
        }

        $kka->delete();

        return redirect()
            ->route('kka.index')
            ->with('success', 'KKA berhasil dihapus.');
    }

    public function ajukan(Kka $kka)
    {
        $user = Auth::user();

        if ($kka->ra_id !== $user->id) {
            abort(403, 'Hanya pembuat KKA yang bisa mengajukan KKA ini.');
        }

        if ($kka->status !== 'draft') {
            abort(422, 'Hanya KKA berstatus draft yang bisa diajukan.');
        }

        $kka->update(['status' => 'submitted']);

        return redirect()
            ->route('kka.show', $kka)
            ->with('success', 'KKA berhasil diajukan ke SKAI Pusat.');
    }

    public function review(Request $request, Kka $kka)
    {
        if (! Auth::user()->isPusat()) {
            abort(403, 'Hanya SKAI Pusat yang berwenang mereview KKA.');
        }

        if (! in_array($kka->status, ['submitted', 'direview'], true)) {
            abort(422, 'KKA ini belum diajukan atau sudah selesai direview.');
        }

        $validated = $request->validate([
            'keputusan' => ['required', 'in:direview,selesai'],
        ]);

        $kka->update(['status' => $validated['keputusan']]);

        return redirect()
            ->route('kka.show', $kka)
            ->with('success', 'Review KKA berhasil disimpan.');
    }

    private function pastikanBolehAkses(Kka $kka): void
    {
        $user = Auth::user();

        if ($user->isPusat()) {
            return;
        }

        $kka->loadMissing('jadwalAudit');

        if ($kka->jadwalAudit->cabang_id !== $user->cabang_id) {
            abort(403, 'Anda tidak memiliki akses ke KKA cabang lain.');
        }
    }
}

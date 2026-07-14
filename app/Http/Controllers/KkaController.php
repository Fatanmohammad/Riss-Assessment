<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKkaRequest;
use App\Models\JadwalAudit;
use App\Models\JawabanKka;
use App\Models\Kka;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Alur maker-checker:
 *  - Staf Cabang (role lingkup=cabang) MEMBUAT & MENGISI KKA untuk cabangnya sendiri (maker),
 *    lalu mengajukannya ke Pusat lewat ajukan().
 *  - SKAI Pusat (role lingkup=pusat) MEREVIEW/APPROVE/TOLAK lewat review() (checker).
 */
class KkaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Kka::with(['jadwalAudit.cabang', 'auditor', 'checker']);

        // Staf cabang hanya melihat KKA milik cabangnya sendiri.
        if ($user->isCabang()) {
            $query->whereHas('jadwalAudit', function ($q) use ($user) {
                $q->where('cabang_id', $user->cabang_id);
            });
        }
        // Pusat (SKAI) bisa melihat KKA dari semua cabang, tanpa filter tambahan.

        $kkas = $query->latest()->paginate(15);

        return view('kka.index', compact('kkas'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->isPusat()) {
            abort(403, 'SKAI Pusat tidak membuat KKA, hanya mereview KKA yang diajukan cabang.');
        }

        // Staf cabang hanya bisa memilih jadwal audit milik cabangnya sendiri.
        $jadwalAudits = JadwalAudit::where('status', 'berjalan')
            ->where('cabang_id', $user->cabang_id)
            ->get();

        return view('kka.create', compact('jadwalAudits'));
    }

    public function store(StoreKkaRequest $request)
    {
        $user = Auth::user();

        if ($user->isPusat()) {
            abort(403, 'SKAI Pusat tidak membuat KKA, hanya mereview KKA yang diajukan cabang.');
        }

        $jadwalAudit = JadwalAudit::findOrFail($request->input('jadwal_audit_id'));

        if ($jadwalAudit->cabang_id !== $user->cabang_id) {
            abort(403, 'Anda hanya bisa mengisi KKA untuk cabang anda sendiri.');
        }

        $validated = $request->validated();
        // Pengisi KKA selalu user yang sedang login, bukan input bebas dari form.
        $validated['auditor_id'] = $user->id;
        $validated['status'] = 'draft';

        $jawabanList = $validated['jawaban'] ?? [];
        unset($validated['jawaban']);

        $kka = DB::transaction(function () use ($validated, $jawabanList, $request) {
            $kka = Kka::create($validated);

            foreach ($jawabanList as $index => $item) {
                $lampiranPath = null;

                if ($request->hasFile("jawaban.$index.lampiran")) {
                    $lampiranPath = $request->file("jawaban.$index.lampiran")
                        ->store('kka-lampiran', 'public');
                }

                JawabanKka::create([
                    'kka_id'             => $kka->id,
                    'pertanyaan_kka_id'  => $item['pertanyaan_kka_id'],
                    'auditor_id'         => $kka->auditor_id,
                    'jawaban'            => $item['jawaban'],
                    'skor'               => $item['skor'] ?? null,
                    'lampiran_path'      => $lampiranPath,
                ]);
            }

            return $kka;
        });

        return redirect()
            ->route('kka.show', $kka)
            ->with('success', 'KKA berhasil disimpan sebagai draft. Jangan lupa ajukan ke Pusat setelah lengkap.');
    }

    public function show(Kka $kka)
    {
        $this->pastikanBolehAkses($kka);

        $kka->load(['jadwalAudit.cabang', 'auditor', 'checker', 'pertanyaan', 'jawaban', 'scoring', 'temuan']);

        return view('kka.show', compact('kka'));
    }

    public function edit(Kka $kka)
    {
        $user = Auth::user();

        if ($kka->auditor_id !== $user->id || $kka->status !== 'draft') {
            abort(403, 'KKA hanya bisa diedit oleh pembuatnya selama masih berstatus draft.');
        }

        $kka->load(['pertanyaan', 'jawaban']);
        $jadwalAudits = JadwalAudit::where('cabang_id', $user->cabang_id)->get();

        return view('kka.edit', compact('kka', 'jadwalAudits'));
    }

    public function update(StoreKkaRequest $request, Kka $kka)
    {
        $user = Auth::user();

        if ($kka->auditor_id !== $user->id || $kka->status !== 'draft') {
            abort(403, 'KKA hanya bisa diubah oleh pembuatnya selama masih berstatus draft.');
        }

        $validated = $request->validated();
        unset($validated['jawaban'], $validated['auditor_id'], $validated['status']);

        $kka->update($validated);

        return redirect()
            ->route('kka.show', $kka)
            ->with('success', 'KKA berhasil diperbarui.');
    }

    public function destroy(Kka $kka)
    {
        $user = Auth::user();

        if ($kka->auditor_id !== $user->id || $kka->status !== 'draft') {
            abort(403, 'KKA hanya bisa dihapus oleh pembuatnya selama masih berstatus draft.');
        }

        $kka->delete();

        return redirect()
            ->route('kka.index')
            ->with('success', 'KKA berhasil dihapus.');
    }

    /**
     * Staf cabang mengajukan KKA yang sudah lengkap diisi ke SKAI Pusat untuk direview.
     */
    public function ajukan(Kka $kka)
    {
        $user = Auth::user();

        if ($kka->auditor_id !== $user->id) {
            abort(403, 'Hanya pembuat KKA yang bisa mengajukan KKA ini.');
        }

        if ($kka->status !== 'draft') {
            abort(422, 'KKA yang bisa diajukan hanya yang masih berstatus draft.');
        }

        $kka->update(['status' => 'diajukan']);

        // TODO: kirim notifikasi ke SKAI Pusat, misal Notification::send($pusatUsers, new KkaBelumDiisiNotification($kka))
        // (atau notifikasi khusus "KKA menunggu review" bila ingin dibuat terpisah).

        return redirect()
            ->route('kka.show', $kka)
            ->with('success', 'KKA berhasil diajukan ke SKAI Pusat untuk direview.');
    }

    /**
     * SKAI Pusat mereview KKA: approve atau tolak (checker).
     */
    public function review(Request $request, Kka $kka)
    {
        $user = Auth::user();

        if (! $user->isPusat()) {
            abort(403, 'Hanya SKAI Pusat yang berwenang mereview KKA.');
        }

        if (! in_array($kka->status, ['diajukan', 'direview'], true)) {
            abort(422, 'KKA ini belum diajukan atau sudah selesai direview.');
        }

        $validated = $request->validate([
            'keputusan'       => ['required', 'in:disetujui,ditolak'],
            'catatan_review'  => ['nullable', 'string'],
        ]);

        $kka->update([
            'checker_id'      => $user->id,
            'status'          => $validated['keputusan'],
            'catatan_review'  => $validated['catatan_review'] ?? null,
            'tanggal_review'  => now(),
        ]);

        return redirect()
            ->route('kka.show', $kka)
            ->with('success', 'Review KKA berhasil disimpan: ' . $validated['keputusan'] . '.');
    }

    /**
     * Otorisasi tampilan: staf cabang hanya boleh lihat KKA cabangnya sendiri,
     * Pusat boleh lihat semua.
     */
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

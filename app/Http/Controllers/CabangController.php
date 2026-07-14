<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Data master Cabang hanya dikelola (create/update/delete) oleh SKAI Pusat.
 * Semua role boleh melihat daftar cabang (index/show).
 */
class CabangController extends Controller
{
    public function index()
    {
        $cabangs = Cabang::orderBy('nama_cabang')->paginate(15);

        return view('cabang.index', compact('cabangs'));
    }

    public function create()
    {
        $this->pastikanPusat();

        return view('cabang.create');
    }

    public function store(Request $request)
    {
        $this->pastikanPusat();

        $validated = $request->validate([
            'kode_cabang'  => ['required', 'string', 'max:20', 'unique:cabangs,kode_cabang'],
            'nama_cabang'  => ['required', 'string', 'max:255'],
            'alamat'       => ['nullable', 'string'],
            'kota'         => ['nullable', 'string', 'max:100'],
            'status'       => ['nullable', 'in:aktif,nonaktif'],
        ]);

        $cabang = Cabang::create($validated);

        return redirect()
            ->route('cabang.index')
            ->with('success', "Cabang \"{$cabang->nama_cabang}\" berhasil ditambahkan.");
    }

    public function show(Cabang $cabang)
    {
        $cabang->load('jadwalAudits');

        return view('cabang.show', compact('cabang'));
    }

    public function edit(Cabang $cabang)
    {
        $this->pastikanPusat();

        return view('cabang.edit', compact('cabang'));
    }

    public function update(Request $request, Cabang $cabang)
    {
        $this->pastikanPusat();

        $validated = $request->validate([
            'kode_cabang'  => ['required', 'string', 'max:20', 'unique:cabangs,kode_cabang,' . $cabang->id],
            'nama_cabang'  => ['required', 'string', 'max:255'],
            'alamat'       => ['nullable', 'string'],
            'kota'         => ['nullable', 'string', 'max:100'],
            'status'       => ['nullable', 'in:aktif,nonaktif'],
        ]);

        $cabang->update($validated);

        return redirect()
            ->route('cabang.index')
            ->with('success', 'Data cabang berhasil diperbarui.');
    }

    public function destroy(Cabang $cabang)
    {
        $this->pastikanPusat();

        $cabang->delete();

        return redirect()
            ->route('cabang.index')
            ->with('success', 'Data cabang berhasil dihapus.');
    }

    private function pastikanPusat(): void
    {
        if (! Auth::user()->isPusat()) {
            abort(403, 'Hanya SKAI Pusat yang berwenang mengelola data cabang.');
        }
    }
}

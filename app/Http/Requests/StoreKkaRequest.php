<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKkaRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Sesuaikan dengan KkaPolicy nantinya (misal: hanya auditor/ketua tim)
        return true;
    }

    public function rules(): array
    {
        return [
            'jadwal_audit_id' => ['required', 'exists:jadwal_audits,id'],
            'auditor_id'      => ['required', 'exists:users,id'],
            'judul'           => ['required', 'string', 'max:255'],
            'status'          => ['nullable', 'in:draft,diajukan,direview,disetujui,ditolak'],

            // Jawaban per pertanyaan (jika dikirim sekaligus dalam satu form)
            'jawaban'                    => ['nullable', 'array'],
            'jawaban.*.pertanyaan_kka_id' => ['required_with:jawaban', 'exists:kka_pertanyaans,id'],
            'jawaban.*.jawaban'           => ['required_with:jawaban', 'string'],
            'jawaban.*.skor'              => ['nullable', 'numeric', 'min:0'],
            'jawaban.*.lampiran'          => ['nullable', 'file', 'max:5120', 'mimes:pdf,jpg,jpeg,png,xlsx,docx'],
        ];
    }

    public function messages(): array
    {
        return [
            'jadwal_audit_id.required' => 'Jadwal audit wajib dipilih.',
            'judul.required'           => 'Judul KKA wajib diisi.',
        ];
    }
}

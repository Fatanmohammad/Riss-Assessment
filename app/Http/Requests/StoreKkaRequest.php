<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKkaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jadwal_audit_id'        => ['required', 'exists:jadwal_audits,id'],
            'status'                 => ['nullable', 'in:draft,submitted,direview,selesai'],

            'jawaban'                => ['nullable', 'array'],
            'jawaban.*.pertanyaan_id'=> ['required_with:jawaban', 'exists:kka_pertanyaans,id'],
            'jawaban.*.jawaban'      => ['required_with:jawaban', 'string'],
            'jawaban.*.nilai'        => ['nullable', 'numeric', 'min:0'],
            'jawaban.*.keterangan'   => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'jadwal_audit_id.required' => 'Jadwal audit wajib dipilih.',
        ];
    }
}

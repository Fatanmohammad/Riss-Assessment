<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTindakLanjutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'temuan_id'            => ['required', 'exists:temuans,id'],
            'penanggung_jawab_id'  => ['required', 'exists:users,id'],
            'rencana_aksi'         => ['required', 'string'],
            'target_tanggal'       => ['required', 'date', 'after_or_equal:today'],
            'tanggal_realisasi'    => ['nullable', 'date'],
            'status'               => ['nullable', 'in:belum_mulai,proses,selesai,terlambat'],
            'bukti_penyelesaian'   => ['nullable', 'file', 'max:5120', 'mimes:pdf,jpg,jpeg,png,xlsx,docx'],
            'catatan'              => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'target_tanggal.after_or_equal' => 'Target tanggal tidak boleh di masa lalu.',
        ];
    }
}

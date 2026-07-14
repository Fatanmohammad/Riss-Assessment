<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJadwalAuditRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cabang_id'        => ['required', 'exists:cabangs,id'],
            'bidang_id'        => ['required', 'exists:bidangs,id'],
            'ketua_tim_id'     => ['required', 'exists:users,id'],
            'periode_audit'    => ['required', 'string', 'max:50'],
            'tanggal_mulai'    => ['required', 'date'],
            'tanggal_selesai'  => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'status'           => ['nullable', 'in:draft,berjalan,selesai,dibatalkan'],
            'catatan'          => ['nullable', 'string'],
            'anggota_tim'      => ['nullable', 'array'],
            'anggota_tim.*'    => ['exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ];
    }
}

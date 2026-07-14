<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLaporanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jadwal_audit_id'      => ['required', 'exists:jadwal_audits,id'],
            'dibuat_oleh'          => ['required', 'exists:users,id'],
            'judul_laporan'        => ['required', 'string', 'max:255'],
            'ringkasan_eksekutif'  => ['nullable', 'string'],
            'status'               => ['nullable', 'in:draft,final,dikirim'],
            'tanggal_terbit'       => ['nullable', 'date'],
        ];
    }
}

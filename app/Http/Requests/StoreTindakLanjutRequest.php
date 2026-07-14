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
            'flag_id'    => ['required', 'exists:flag_kejanggalans,id'],
            'checker_id' => ['required', 'exists:users,id'],
            'keterangan' => ['required', 'string'],
            'bukti_fisik'=> ['nullable', 'file', 'max:5120', 'mimes:pdf,jpg,jpeg,png,xlsx,docx'],
            'status'     => ['nullable', 'in:proses,selesai'],
        ];
    }
}

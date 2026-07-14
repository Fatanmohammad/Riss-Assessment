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
            'jenis'     => ['required', 'in:bulanan,triwulan'],
            'periode'   => ['required', 'string', 'max:20'],
            'cabang_id' => ['nullable', 'exists:cabangs,id'],
            'ra_id'     => ['nullable', 'exists:users,id'],
            'file_path' => ['nullable', 'string'],
        ];
    }
}

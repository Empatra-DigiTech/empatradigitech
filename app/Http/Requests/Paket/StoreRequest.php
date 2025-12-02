<?php

namespace App\Http\Requests\Paket;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipe' => 'required|in:website,app',
            'nama_paket' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'harga' => 'required|numeric|min:0',
            'periode' => 'nullable|string|max:100',
            'fitur' => 'nullable|array',
            'fitur.*' => 'string',
            'is_recommended' => 'boolean',
            'is_active' => 'boolean',
            'urutan' => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'tipe.required' => 'Tipe paket harus dipilih',
            'tipe.in' => 'Tipe paket harus website atau app',
            'nama_paket.required' => 'Nama paket harus diisi',
            'harga.required' => 'Harga harus diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga minimal 0',
        ];
    }
}

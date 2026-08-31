<?php

namespace App\Http\Requests\ClientLogo;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama_client' => ['required'],
            'website_url' => ['nullable', 'url'],
            'logo' => ['required', 'image', 'mimes:jpeg,bmp,png,gif,svg,jpg', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_client.required' => 'Nama client harus diisi',
            'website_url.url' => 'Format URL tidak valid (contoh: https://namaperusahaan.com)',
            'logo.required' => 'Logo harus diunggah',
            'logo.image' => 'Logo harus berupa gambar',
            'logo.mimes' => 'Logo harus berupa jpeg, bmp, png, gif, svg, jpg',
            'logo.max' => 'Logo tidak boleh lebih dari 2MB',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function failedValidation(Validator $validator)
    {
        if (! $this->wantsJson()) {
            $errors = implode('<br>', $validator->errors()->all());
            alert()->html('Gagal', $errors, 'error');
            $this->redirect = route('patra.client-logo.create');
        }

        parent::failedValidation($validator);
    }
}

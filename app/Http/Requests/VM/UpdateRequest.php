<?php

namespace App\Http\Requests\VM;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'visi' => [
                'required',
                'string',
                'min:10',
                'max:1000',
            ],
            'misi' => [
                'required',
                'string',
                'min:10',
                'max:5000',
            ],
            'image' => [
                'nullable',
                'image',
                'max:2048',
                'mimes:jpeg,bmp,png,gif,svg,jpg',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'visi.required' => 'Visi harus diisi',
            'visi.string' => 'Visi harus berupa teks',
            'visi.min' => 'Visi minimal 10 karakter',
            'visi.max' => 'Visi maksimal 1000 karakter',

            'misi.required' => 'Misi harus diisi',
            'misi.string' => 'Misi harus berupa teks',
            'misi.min' => 'Misi minimal 10 karakter',
            'misi.max' => 'Misi maksimal 5000 karakter',

            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Gambar harus berupa jpeg, bmp, png, gif, svg, jpg',
            'image.max' => 'Gambar tidak boleh lebih dari 2MB',
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
            $this->redirect = route('patra.vm.index');
        }

        parent::failedValidation($validator);
    }
}

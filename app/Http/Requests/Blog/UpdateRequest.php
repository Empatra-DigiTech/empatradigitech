<?php

namespace App\Http\Requests\Blog;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => [
                'required',
            ],
            'kategori' => [
                'required',
            ],
            'excerpt' => [
                'required',
                'max:160',
            ],
            'image' => [
                'image',
                'max:2048',
                'mimes:jpeg,bmp,png,gif,svg,jpg',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul harus diisi',
            'kategori.required' => 'Kategori harus diisi',
            'excerpt.required' => 'Ringkasan (excerpt) harus diisi',
            'excerpt.max' => 'Ringkasan maksimal 160 karakter (untuk SEO meta description)',
            'image.image' => 'Foto harus berupa gambar',
            'image.mimes' => 'Foto harus berupa jpeg, bmp, png, gif, svg , jpg',
            'image.max' => 'Foto tidak boleh lebih dari 2MB',
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
            alert()->html('Gagal',$errors,'error');
            $this->redirect = route('patra.blog.edit', request()->route()->parameter('id'));
        }

        parent::failedValidation($validator);
    }
}

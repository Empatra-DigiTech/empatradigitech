<?php

namespace App\Http\Requests\Team;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama' => [
                'required',
                'string',
                'max:255',
            ],
            'jabatan' => [
                'required',
                'string',
                'max:255',
            ],
            'linkedin' => [
                'nullable',
                'url',
                'max:500',
            ],
            'instagram' => [
                'nullable',
                'url',
                'max:500',
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
            'nama.required' => 'Nama harus diisi',
            'nama.string' => 'Nama harus berupa teks',
            'nama.max' => 'Nama tidak boleh lebih dari 255 karakter',

            'jabatan.required' => 'Jabatan harus diisi',
            'jabatan.string' => 'Jabatan harus berupa teks',
            'jabatan.max' => 'Jabatan tidak boleh lebih dari 255 karakter',

            'linkedin.url' => 'LinkedIn harus berupa URL yang valid',
            'linkedin.max' => 'LinkedIn URL tidak boleh lebih dari 500 karakter',

            'instagram.url' => 'Instagram harus berupa URL yang valid',
            'instagram.max' => 'Instagram URL tidak boleh lebih dari 500 karakter',

            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Foto harus berupa jpeg, bmp, png, gif, svg, jpg',
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
            $this->redirect = route('patra.team.edit', request()->route()->parameter('id'));
        }

        parent::failedValidation($validator);
    }
}

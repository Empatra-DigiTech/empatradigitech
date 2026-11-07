<?php

namespace App\Http\Requests\Galeri;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;


class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'image' => [
                'required',
                'max:51200',
                'mimes:jpeg,png,gif,jpg,mp4,mkv,wmv,avi',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul harus diisi',
            'description.required' => 'Deskripsi harus diisi',
            'date.required' => 'Tanggal harus diisi',
            'image.required' => 'File harus diisi',
            'image.mimes' => 'File harus berupa jpeg, png, gif, jpg, mp4, mkv, wmv, avi',
            'image.max' => 'File tidak boleh lebih dari 50MB',
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
            $this->redirect = route('patra.galeri.create');
        }

        parent::failedValidation($validator);
    }
}

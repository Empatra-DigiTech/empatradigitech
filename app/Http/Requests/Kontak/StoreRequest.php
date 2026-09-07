<?php

namespace App\Http\Requests\Kontak;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
            ],
            // 'description'=> [
            //     'required',
            //     ''
            //     ],
            'image' => [
                'image',
                'max:5048',
                'mimes:jpeg,bmp,png,gif,svg,jpg',
            ],
            'email' => [
                'email',
                'required',
            ],
            'subject' => [
                'required',
                'max:100',
            ],
            'message' => [
                'required',
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama harus diisi',
            // 'description.required' => 'Deksripsi harus diisi',
            'email.required' => 'Email harus diisi',
            'image.mimes' => 'Foto harus berupa jpeg, bmp, png, gif, svg , jpg',
            'image.max' => 'Foto tidak boleh lebih dari 5MB',
            'subject.required' => 'Subject harus diisi',
            'subject.max' => 'Subject tidak lebih dari 100 karakter',
            'message.required' => 'Pesan harus diisi',
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
            // FIXED: sebelumnya selalu redirect ke homepage saat validasi gagal
            // (kemungkinan karena halaman home.kontak.index dulu belum ada).
            // Sekarang halamannya sudah ada, jadi user dikembalikan ke form
            // kontak (dengan input lama tetap terisi) alih-alih dilempar ke home.
            $this->redirect = route('home.kontak.index');
        }

        parent::failedValidation($validator);
    }
}

<?php

namespace App\Http\Requests\Kontak;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /** Captcha hanya diwajibkan bila kunci reCAPTCHA terisi di .env (NOCAPTCHA_SECRET & NOCAPTCHA_SITEKEY). */
    public static function captchaEnabled(): bool
    {
        return \App\Support\Captcha::enabled();
    }

    public function rules(): array
    {
        $captcha = self::captchaEnabled()
            ? ['g-recaptcha-response' => ['required', 'captcha']]
            : [];

        return $captcha + [
            'name' => [
                'required',
                'string',
                'max:100',
            ],
            // 'description'=> [
            //     'required',
            //     ''
            //     ],
            'image' => [
                'image',
                'max:5048',
                'mimes:jpeg,png,gif,jpg',
            ],
            'email' => [
                'email',
                'required',
                'max:255',
            ],
            'subject' => [
                'required',
                'max:100',
            ],
            'message' => [
                'required',
                'string',
                'max:2000',
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama harus diisi',
            'name.max' => 'Nama tidak lebih dari 100 karakter',
            'g-recaptcha-response.required' => 'Captcha harus diisi',
            'g-recaptcha-response.captcha' => 'Verifikasi captcha gagal, silakan coba lagi',
            'email.max' => 'Email tidak lebih dari 255 karakter',
            'message.max' => 'Pesan tidak lebih dari 2000 karakter',
            // 'description.required' => 'Deksripsi harus diisi',
            'email.required' => 'Email harus diisi',
            'image.mimes' => 'Foto harus berupa jpeg, png, gif, jpg',
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

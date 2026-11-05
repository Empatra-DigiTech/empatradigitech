<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator; 


class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Captcha hanya diwajibkan bila kunci reCAPTCHA terisi di .env (lihat App\Support\Captcha).
        $captcha = \App\Support\Captcha::enabled()
            ? ['g-recaptcha-response' => ['required', 'captcha']]
            : [];

        return $captcha + [
            'email' => [
            'required',
            'email',
            ],
            // Tanpa min:8 di sini: aturan panjang hanya untuk membuat/ganti password,
            // supaya akun lama dengan password lebih pendek tetap bisa masuk.
            'password' => [
            'required',
            ]
        ]; 
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Field harus berupa email',
            'password.required' => 'Password harus diisi',
            'g-recaptcha-response.required' => 'Captcha harus diisi',
            'g-recaptcha-response.captcha' => 'Verifikasi captcha gagal, silakan coba lagi'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        if (! $this->wantsJson()) {
            $errors = implode('<br>', $validator->errors()->all());
            alert()->html('Gagal',$errors,'error');
            $this->redirect = url('/auth/login');
        }
        parent::failedValidation($validator);

    }
}

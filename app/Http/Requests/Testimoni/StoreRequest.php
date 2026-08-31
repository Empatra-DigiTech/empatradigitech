<?php

namespace App\Http\Requests\Testimoni;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama_client' => ['required'],
            'jabatan' => ['nullable'],
            'perusahaan' => ['nullable'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'testimoni' => ['required'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,bmp,png,gif,svg,jpg', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_client.required' => 'Nama client harus diisi',
            'rating.required' => 'Rating harus diisi',
            'rating.integer' => 'Rating harus berupa angka',
            'rating.min' => 'Rating minimal 1',
            'rating.max' => 'Rating maksimal 5',
            'testimoni.required' => 'Isi testimoni harus diisi',
            'foto.image' => 'Foto harus berupa gambar',
            'foto.mimes' => 'Foto harus berupa jpeg, bmp, png, gif, svg, jpg',
            'foto.max' => 'Foto tidak boleh lebih dari 2MB',
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
            $this->redirect = route('patra.testimoni.create');
        }

        parent::failedValidation($validator);
    }
}

<?php

namespace App\Http\Requests\Informasi;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\SafeUrl;


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
            // Salah satu wajib: file ATAU link (sebelumnya keduanya kosong tetap "berhasil" tanpa menyimpan apa-apa).
            'file_link' => [
                'nullable',
                'string',
                'max:500',
                'required_without:file_path',
                new SafeUrl,
            ],
            'file_path' => [
                'nullable',
                'required_without:file_link',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,jpg,jpeg,png',
                'max:102400',
            ],
            // 'file_path' => [
            //     'required',
            // ],
            // 'file_link' => [
            //     'required',
            // ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul harus diisi',
            'title.max' => 'Judul tidak boleh lebih dari 255 karakter',
            'file_link.required_without' => 'Isi link atau unggah file',
            'file_link.max' => 'Link tidak boleh lebih dari 500 karakter',
            'file_path.required_without' => 'Unggah file atau isi link',
            'file_path.max' => 'File tidak boleh lebih dari 100MB',
            'file_path.mimes' => 'File harus berupa pdf, doc, docx, xls, xlsx, ppt, pptx, zip, jpg, jpeg, png',
            // 'file_path.required' => 'File harus diisi',
            // 'file_link.required' => 'Link harus diisi',
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
            $this->redirect = route('patra.informasi.create');
        }

        parent::failedValidation($validator);
    }
}

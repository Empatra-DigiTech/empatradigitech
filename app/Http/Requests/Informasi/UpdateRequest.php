<?php

namespace App\Http\Requests\Informasi;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\SafeUrl;


class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'file_link' => [
                'nullable',
                'string',
                'max:500',
                new SafeUrl,
            ],
            'file_path' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,jpg,jpeg,png',
                'max:102400',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul harus diisi',
            'title.max' => 'Judul tidak boleh lebih dari 255 karakter',
            'file_link.max' => 'Link tidak boleh lebih dari 500 karakter',
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

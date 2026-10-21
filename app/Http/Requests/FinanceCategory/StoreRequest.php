<?php

namespace App\Http\Requests\FinanceCategory;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'type' => ['required', 'in:masuk,keluar'],
            'parent_id' => ['nullable', 'exists:finance_categories,id'],
            'urutan' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori harus diisi',
            'type.required' => 'Jenis kategori harus dipilih',
            'type.in' => 'Jenis kategori tidak valid',
            'parent_id.exists' => 'Kategori induk tidak valid',
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
            $this->redirect = route('patra.finance-category.create');
        }

        parent::failedValidation($validator);
    }
}

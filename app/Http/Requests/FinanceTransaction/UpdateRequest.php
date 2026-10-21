<?php

namespace App\Http\Requests\FinanceTransaction;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'description' => ['required', 'string', 'max:255'],
            'finance_category_id' => ['required', 'exists:finance_categories,id'],
            'type' => ['required', 'in:masuk,keluar'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'Tanggal harus diisi',
            'description.required' => 'Deskripsi harus diisi',
            'finance_category_id.required' => 'Kategori harus dipilih',
            'finance_category_id.exists' => 'Kategori tidak valid',
            'type.required' => 'Jenis transaksi harus dipilih',
            'amount.required' => 'Jumlah harus diisi',
            'amount.numeric' => 'Jumlah harus berupa angka',
            'amount.min' => 'Jumlah harus lebih dari 0',
            'payment_method.required' => 'Metode bayar harus dipilih',
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
            $this->redirect = route('patra.finance.edit', request()->route()->parameter('id'));
        }

        parent::failedValidation($validator);
    }
}

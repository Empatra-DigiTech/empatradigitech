<?php

namespace App\Http\Requests\Menu;

use App\Http\Requests\Concerns\ValidatesMenuParent;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    use ValidatesMenuParent;

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:16'],
            'parent' => ['nullable', 'integer', 'exists:menus,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Menu harus diisi',
            'title.max' => 'Menu tidak lebih dari 16 karakter',
            'parent.exists' => 'Induk menu tidak valid',
            'parent.integer' => 'Induk menu tidak valid',
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
            $this->redirect = route('patra.menu.create');
        }

        parent::failedValidation($validator);
    }
}

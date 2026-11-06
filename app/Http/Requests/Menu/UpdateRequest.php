<?php

namespace App\Http\Requests\Menu;

use App\Http\Requests\Concerns\ValidatesMenuParent;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    use ValidatesMenuParent;

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', $this->titleMaxRule()],
            'parent' => ['nullable', 'integer', 'exists:menus,id'],
            // 'description'=> [
            //     'required',
            //     ],
            'image' => [
                'image',
                'max:2048',
                'mimes:jpeg,png,gif,jpg',
            ],
        ];
    }

    /**
     * Batas 16 karakter sama seperti saat membuat menu. Menu lama yang sudah lebih panjang
     * tetap bisa diedit selama judulnya tidak diubah.
     */
    private function titleMaxRule(): string
    {
        $current = \App\Models\Menu::find($this->route('id'));

        return $current && $current->title === $this->input('title') ? 'max:255' : 'max:16';
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul harus diisi',
            'title.max' => 'Menu tidak lebih dari 16 karakter',
            'parent.exists' => 'Induk menu tidak valid',
            'parent.integer' => 'Induk menu tidak valid',
            'description.required' => 'Deksripsi harus diisi',
            'image.image' => 'Foto harus berupa gambar',
            'image.mimes' => 'Foto harus berupa jpeg, png, gif, jpg',
            'image.max' => 'Foto tidak boleh lebih dari 2MB',
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
            $this->redirect = route('patra.menu.edit', request()->route()->parameter('id'));
        }

        parent::failedValidation($validator);
    }
}

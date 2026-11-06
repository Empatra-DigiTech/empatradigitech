<?php

namespace App\Http\Requests\Concerns;

use App\Models\Menu;
use Illuminate\Validation\Validator;

/**
 * Aturan induk menu: induk harus menu level teratas (maks. 2 level, navbar publik
 * hanya menampilkan 2 level), tidak boleh diri sendiri, dan menu yang sudah punya
 * sub tidak boleh dijadikan sub.
 */
trait ValidatesMenuParent
{
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {
            $parentId = $this->input('parent');
            if (blank($parentId) || $v->errors()->has('parent')) {
                return;
            }

            $parent = Menu::find($parentId);
            if (! $parent) {
                return;
            }

            $selfId = $this->route('id');
            $self = $selfId ? Menu::find($selfId) : null;

            if ($self && $parent->id === $self->id) {
                $v->errors()->add('parent', 'Menu tidak boleh menjadi induk bagi dirinya sendiri');
            } elseif ($parent->parent !== null) {
                $v->errors()->add('parent', 'Induk harus menu level teratas (maksimal 2 level)');
            } elseif ($self && Menu::where('parent', $self->id)->exists()) {
                $v->errors()->add('parent', 'Menu ini punya sub menu sehingga tidak bisa dijadikan sub menu');
            }
        });
    }
}

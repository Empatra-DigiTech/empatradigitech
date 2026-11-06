<?php

namespace App\Http\Requests\Concerns;

use App\Models\FinanceCategory;
use Illuminate\Validation\Validator;

/**
 * Aturan hierarki kategori keuangan:
 *  - maksimal 2 level (induk -> sub),
 *  - jenis sub kategori sama dengan induknya,
 *  - kategori tidak boleh jadi induk dirinya sendiri,
 *  - kategori yang sudah punya sub tidak boleh jadi sub,
 *  - jenis tidak boleh diubah bila sudah dipakai transaksi atau punya sub.
 */
trait ValidatesFinanceCategoryHierarchy
{
    /** Kategori yang sedang diedit (null saat membuat baru). */
    protected function editedCategory(): ?FinanceCategory
    {
        $id = $this->route('id');

        return $id ? FinanceCategory::find($id) : null;
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {
            $self = $this->editedCategory();
            $type = $this->input('type');
            $parentId = $this->input('parent_id');

            if (filled($parentId) && ! $v->errors()->has('parent_id')) {
                $parent = FinanceCategory::find($parentId);

                if ($parent) {
                    if ($self && $parent->id === $self->id) {
                        $v->errors()->add('parent_id', 'Kategori tidak boleh menjadi induk bagi dirinya sendiri');
                    } elseif ($parent->parent_id !== null) {
                        $v->errors()->add('parent_id', 'Kategori induk harus kategori level teratas (maksimal 2 level)');
                    } elseif ($parent->type !== $type) {
                        $v->errors()->add('parent_id', 'Jenis kategori harus sama dengan jenis kategori induk');
                    } elseif ($self && $self->children()->exists()) {
                        $v->errors()->add('parent_id', 'Kategori ini punya sub kategori sehingga tidak bisa dijadikan sub kategori');
                    }
                }
            }

            if ($self && $type !== $self->type && ! $v->errors()->has('type')) {
                if ($self->transactions()->exists()) {
                    $v->errors()->add('type', 'Jenis kategori tidak bisa diubah karena sudah dipakai di transaksi');
                } elseif ($self->children()->exists()) {
                    $v->errors()->add('type', 'Jenis kategori tidak bisa diubah karena masih punya sub kategori');
                }
            }
        });
    }
}

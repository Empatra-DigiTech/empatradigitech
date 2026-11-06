<?php

namespace App\Http\Requests\Concerns;

use App\Models\FinanceCategory;
use App\Models\FinanceTransaction;
use Illuminate\Validation\Validator;

/**
 * Pemeriksaan kategori pada form transaksi keuangan:
 *  - jenis kategori harus sama dengan jenis transaksi,
 *  - kategori harus aktif. Pengecualian saat edit: transaksi boleh tetap berada
 *    di kategori yang kini nonaktif (data lama tidak ikut "rusak").
 */
trait ValidatesFinanceTransactionCategory
{
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {
            if ($v->errors()->has('finance_category_id') || $v->errors()->has('type')) {
                return;
            }

            $category = FinanceCategory::find($this->input('finance_category_id'));
            if (! $category) {
                return;
            }

            if ($category->type !== $this->input('type')) {
                $v->errors()->add(
                    'finance_category_id',
                    "Jenis kategori \"{$category->name}\" ({$category->type}) tidak sesuai dengan jenis transaksi ({$this->input('type')})"
                );

                return;
            }

            if (! $category->is_active && ! $this->isCurrentCategoryOfTransaction($category)) {
                $v->errors()->add('finance_category_id', "Kategori \"{$category->name}\" sudah nonaktif");
            }
        });
    }

    private function isCurrentCategoryOfTransaction(FinanceCategory $category): bool
    {
        $id = $this->route('id');
        if (! $id) {
            return false;
        }

        $current = FinanceTransaction::withTrashed()->find($id);

        return $current !== null && (int) $current->finance_category_id === (int) $category->id;
    }
}

<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Basis validasi invoice (dipakai Store & Update).
 *
 * `items` dikirim browser sebagai string JSON. Di sini di-decode, divalidasi, lalu
 * `amount` setiap baris DIHITUNG ULANG di server (quantity x rate) — nilai `amount`
 * dari browser diabaikan sehingga tidak bisa dipalsukan.
 */
abstract class InvoiceRequest extends FormRequest
{
    protected string $failedRoute = 'patra.invoice.create';

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $items = $this->input('items');

        if (is_string($items)) {
            $decoded = json_decode($items, true);
            $items = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : null;
        }

        $this->merge(['items' => $items]);
    }

    abstract protected function invoiceNumberRule(): array;

    public function rules(): array
    {
        return [
            'invoice_number' => $this->invoiceNumberRule(),
            'from_name' => ['nullable', 'string', 'max:255'],
            'from_address' => ['nullable', 'string', 'max:2000'],
            'bill_to_name' => ['required', 'string', 'max:255'],
            'bill_to_address' => ['nullable', 'string', 'max:2000'],
            'ship_to_name' => ['nullable', 'string', 'max:255'],
            'ship_to_address' => ['nullable', 'string', 'max:2000'],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:invoice_date'],
            'payment_terms' => ['nullable', 'string', 'max:255'],
            'po_number' => ['nullable', 'string', 'max:255'],
            'items' => ['required', 'array', 'min:1', 'max:200'],
            'items.*.description' => ['required', 'string', 'max:500'],
            'items.*.quantity' => ['required', 'numeric', 'gt:0', 'max:1000000'],
            'items.*.rate' => ['required', 'numeric', 'min:0', 'max:100000000000'],
            'tax_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'discount_amount' => ['nullable', 'numeric', 'min:0', 'max:100000000000'],
            'shipping_amount' => ['nullable', 'numeric', 'min:0', 'max:100000000000'],
            'amount_paid' => ['nullable', 'numeric', 'min:0', 'max:100000000000'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'terms' => ['nullable', 'string', 'max:5000'],
            'currency' => ['nullable', Rule::in(['IDR', 'USD', 'EUR', 'GBP'])],
            'theme' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'invoice_number.required' => 'Nomor invoice harus diisi',
            'invoice_number.unique' => 'Nomor invoice sudah dipakai',
            'bill_to_name.required' => 'Nama penerima tagihan harus diisi',
            'invoice_date.required' => 'Tanggal invoice harus diisi',
            'invoice_date.date' => 'Tanggal invoice tidak valid',
            'due_date.after_or_equal' => 'Jatuh tempo tidak boleh sebelum tanggal invoice',
            'items.required' => 'Item invoice tidak valid atau kosong',
            'items.array' => 'Item invoice tidak valid',
            'items.min' => 'Minimal satu item invoice',
            'items.*.description.required' => 'Deskripsi setiap item harus diisi',
            'items.*.quantity.required' => 'Jumlah setiap item harus diisi',
            'items.*.quantity.numeric' => 'Jumlah item harus berupa angka',
            'items.*.quantity.gt' => 'Jumlah item harus lebih dari 0',
            'items.*.rate.required' => 'Harga setiap item harus diisi',
            'items.*.rate.numeric' => 'Harga item harus berupa angka',
            'items.*.rate.min' => 'Harga item tidak boleh negatif',
            'tax_percentage.max' => 'Pajak maksimal 100%',
            'currency.in' => 'Mata uang tidak valid',
        ];
    }

    /** Data siap simpan: field terpilih + items yang sudah dinormalisasi. */
    public function invoiceData(): array
    {
        $v = $this->validated();

        $v['items'] = collect($v['items'])->map(fn ($i) => [
            'description' => $i['description'],
            'quantity' => (float) $i['quantity'],
            'rate' => (float) $i['rate'],
            'amount' => round((float) $i['quantity'] * (float) $i['rate'], 2),
        ])->values()->all();

        foreach (['tax_percentage', 'discount_amount', 'shipping_amount', 'amount_paid'] as $k) {
            $v[$k] = $v[$k] ?? 0;
        }
        $v['currency'] = $v['currency'] ?? 'IDR';
        $v['theme'] = $v['theme'] ?? 'classic';

        return $v;
    }

    public function failedValidation(Validator $validator)
    {
        if (! $this->wantsJson()) {
            $errors = implode('<br>', $validator->errors()->all());
            alert()->html('Gagal', $errors, 'error');
            $this->redirect = $this->failureUrl();
        }

        parent::failedValidation($validator);
    }

    protected function failureUrl(): string
    {
        return route($this->failedRoute);
    }
}

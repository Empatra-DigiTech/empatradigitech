<?php

namespace App\Http\Requests\Invoice;

class UpdateRequest extends InvoiceRequest
{
    protected function invoiceNumberRule(): array
    {
        return ['required', 'string', 'max:100', 'unique:invoices,invoice_number,' . (int) $this->route('id')];
    }

    protected function failureUrl(): string
    {
        return route('patra.invoice.edit', $this->route('id'));
    }
}

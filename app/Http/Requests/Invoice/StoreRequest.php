<?php

namespace App\Http\Requests\Invoice;

class StoreRequest extends InvoiceRequest
{
    protected function invoiceNumberRule(): array
    {
        return ['required', 'string', 'max:100', 'unique:invoices,invoice_number'];
    }
}

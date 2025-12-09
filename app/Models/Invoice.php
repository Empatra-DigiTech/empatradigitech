<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Invoice extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'invoice_number',
        'from_name',
        'from_address',
        'bill_to_name',
        'bill_to_address',
        'ship_to_name',
        'ship_to_address',
        'invoice_date',
        'payment_terms',
        'due_date',
        'po_number',
        'items',
        'subtotal',
        'tax_percentage',
        'tax_amount',
        'discount_amount',
        'shipping_amount',
        'total',
        'amount_paid',
        'balance_due',
        'notes',
        'terms',
        'currency',
        'theme',
    ];

    protected $casts = [
        'items' => 'array',
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance_due' => 'decimal:2',
    ];

    public function calculateTotals()
    {
        $items = $this->items ?? [];
        $subtotal = 0;

        foreach ($items as $item) {
            $quantity = $item['quantity'] ?? 0;
            $rate = $item['rate'] ?? 0;
            $subtotal += $quantity * $rate;
        }

        $this->subtotal = $subtotal;

        $taxPercentage   = $this->tax_percentage   ?? 0;
        $discountAmount  = $this->discount_amount  ?? 0;
        $shippingAmount  = $this->shipping_amount  ?? 0;
        $amountPaid      = $this->amount_paid      ?? 0;

        $taxAmount = ($subtotal * $taxPercentage) / 100;
        $this->tax_amount = $taxAmount;

        $this->total = $subtotal + $taxAmount - $discountAmount + $shippingAmount;
        $this->balance_due = $this->total - $amountPaid;
    }

    public static function generateInvoiceNumber()
    {
        $lastInvoice = self::orderBy('id', 'desc')->first();

        if (!$lastInvoice) {
            return 'INV-' . date('Ym') . '-0001';
        }

        $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
        $newNumber = $lastNumber + 1;

        return 'INV-' . date('Ym') . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function formatCurrency($amount)
    {
        $amount = $amount ?? 0;

        if ($this->currency === 'IDR') {
            return 'Rp ' . number_format($amount, 0, ',', '.');
        }

        $symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
        ];

        $symbol = $symbols[$this->currency] ?? '';

        return $symbol . number_format($amount, 2, '.', ',');
    }
}

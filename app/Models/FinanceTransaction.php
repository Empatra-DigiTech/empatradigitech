<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class FinanceTransaction extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'transaction_number',
        'date',
        'description',
        'finance_category_id',
        'type',
        'amount',
        'payment_method',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(FinanceCategory::class, 'finance_category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Nomor transaksi berurutan, cth: ED-0052.
     * Melanjutkan format di pembukuan Excel lama (ED-0001 ... ED-0051), tanpa reset tahunan.
     */
    public static function generateTransactionNumber(): string
    {
        $prefix = 'ED-';

        $max = self::where('transaction_number', 'like', $prefix . '%')
            ->pluck('transaction_number')
            ->map(fn ($n) => (int) substr($n, strlen($prefix)))
            ->max() ?? 0;

        return $prefix . str_pad((string) ($max + 1), 4, '0', STR_PAD_LEFT);
    }
}

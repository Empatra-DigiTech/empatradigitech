<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FinanceTransaction extends Model
{
    use HasFactory, Loggable, SoftDeletes;

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
        'deleted_by',
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

    private const NUMBER_PREFIX = 'ED-';

    /**
     * Urutan terbesar dari semua nomor berformat ED-NNNN (termasuk yang sudah dihapus, karena nomor
     * tidak boleh dipakai ulang). Satu query agregat; bila driver database tidak mendukungnya,
     * jatuh ke cara lama (baca semua nomor).
     */
    public static function maxSequence(): int
    {
        $prefix = self::NUMBER_PREFIX;

        try {
            $cast = in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'], true) ? 'UNSIGNED' : 'INTEGER';

            $row = self::withTrashed()
                ->where('transaction_number', 'like', $prefix . '%')
                ->toBase()
                ->selectRaw("MAX(CAST(SUBSTR(transaction_number, ?) AS {$cast})) as max_seq", [strlen($prefix) + 1])
                ->first();

            return (int) ($row->max_seq ?? 0);
        } catch (QueryException $e) {
            Log::warning('maxSequence: query agregat gagal, memakai cara lama: ' . $e->getMessage());

            return (int) (self::withTrashed()->where('transaction_number', 'like', $prefix . '%')
                ->pluck('transaction_number')
                ->map(fn ($n) => (int) substr($n, strlen($prefix)))
                ->max() ?? 0);
        }
    }

    public static function formatNumber(int $sequence): string
    {
        return self::NUMBER_PREFIX . str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Nomor transaksi berurutan, cth: ED-0052.
     * Melanjutkan format di pembukuan Excel lama (ED-0001 ... ED-0051), tanpa reset tahunan.
     * Hanya untuk SARAN nomor (mis. di form). Untuk menyimpan, pakai createWithAutoNumber().
     */
    public static function generateTransactionNumber(): string
    {
        return self::formatNumber(self::maxSequence() + 1);
    }

    /**
     * Simpan transaksi dengan nomor otomatis yang aman dari benturan: bila dua request mengambil nomor
     * yang sama, yang kalah (UNIQUE) mengulang dengan nomor berikutnya, bukan gagal.
     *
     * @param  int|null  $cursor  urutan terakhir yang dipakai. Beri variabel yang sama pada pemanggilan
     *                            berturut-turut (impor banyak baris) supaya nomor terbesar dibaca sekali saja.
     */
    public static function createWithAutoNumber(array $attributes, ?int &$cursor = null): self
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $sequence = ($cursor ?? self::maxSequence()) + 1;

            try {
                // Transaksi bersarang = savepoint: insert yang gagal tidak merusak transaksi pemanggil.
                $created = DB::transaction(fn () => self::create($attributes + ['transaction_number' => self::formatNumber($sequence)]));
                $cursor = $sequence;

                return $created;
            } catch (UniqueConstraintViolationException $e) {
                $cursor = null; // kalah balapan: baca ulang nomor terbesar
            }
        }

        throw new \App\Exceptions\UserFacingException('Gagal membuat nomor transaksi karena terlalu banyak transaksi bersamaan. Silakan coba lagi.');
    }
}

<?php

namespace App\Models;

use Carbon\Carbon;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class FinancePeriod extends Model
{
    use Loggable;

    protected $fillable = [
        'period', 'is_closed', 'closed_at', 'closed_by', 'reopened_at', 'reopened_by', 'reopen_reason',
    ];

    protected $casts = [
        'is_closed' => 'boolean',
        'closed_at' => 'datetime',
        'reopened_at' => 'datetime',
    ];

    public function closer()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function reopener()
    {
        return $this->belongsTo(User::class, 'reopened_by');
    }

    /** Apakah bulan dari tanggal ini sudah ditutup? */
    public static function isClosedFor($date): bool
    {
        return static::where('period', Carbon::parse($date)->format('Y-m'))
            ->where('is_closed', true)
            ->exists();
    }

    /** Daftar periode (YYYY-MM) yang sedang tertutup. */
    public static function closedPeriods(): array
    {
        return static::where('is_closed', true)->pluck('period')->all();
    }
}

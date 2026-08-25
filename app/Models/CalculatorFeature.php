<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class CalculatorFeature extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'nama_fitur',
        'harga_tambahan',
        'deskripsi',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'harga_tambahan' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class CalculatorService extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'nama_layanan',
        'harga_dasar',
        'harga_per_halaman',
        'deskripsi',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'harga_dasar' => 'decimal:2',
        'harga_per_halaman' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Paket extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'tipe',
        'nama_paket',
        'tagline',
        'harga',
        'periode',
        'fitur',
        'is_recommended',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'fitur' => 'array',
        'is_recommended' => 'boolean',
        'is_active' => 'boolean',
        'harga' => 'decimal:2',
    ];

    // Scope untuk filter berdasarkan tipe
    public function scopeTipeWebsite($query)
    {
        return $query->where('tipe', 'website');
    }

    public function scopeTipeApp($query)
    {
        return $query->where('tipe', 'app');
    }

    // Scope untuk yang aktif saja
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk recommended
    public function scopeRecommended($query)
    {
        return $query->where('is_recommended', true);
    }

    // Format harga ke Rupiah
    public function getFormattedHargaAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    // Get fitur sebagai array (jika tersimpan sebagai JSON string)
    public function getFiturListAttribute()
    {
        if (is_string($this->fitur)) {
            return json_decode($this->fitur, true) ?? [];
        }
        return $this->fitur ?? [];
    }
}

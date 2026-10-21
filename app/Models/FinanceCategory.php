<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'parent_id',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(FinanceCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(FinanceCategory::class, 'parent_id')->orderBy('urutan');
    }

    public function transactions()
    {
        return $this->hasMany(FinanceTransaction::class);
    }

    /**
     * Nama lengkap "Induk > Anak" untuk ditampilkan di dropdown/laporan.
     */
    public function getFullNameAttribute(): string
    {
        return $this->parent ? $this->parent->name . ' > ' . $this->name : $this->name;
    }
}

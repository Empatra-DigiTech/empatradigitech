<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * Pencarian LIKE yang memperlakukan % dan _ sebagai huruf biasa.
 * Memakai ESCAPE '!' eksplisit sehingga berperilaku sama di MySQL dan SQLite
 * (SQLite tidak punya karakter escape bawaan).
 */
class Like
{
    public static function escape(string $term): string
    {
        return str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $term);
    }

    /** @param Builder|QueryBuilder $query */
    public static function contains($query, string $column, string $term)
    {
        return $query->whereRaw("{$column} LIKE ? ESCAPE '!'", ['%' . self::escape($term) . '%']);
    }
}

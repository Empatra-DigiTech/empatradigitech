<?php

namespace App\Support;

use App\Models\Banner;
use App\Models\Kalender;
use App\Models\Layanan;
use App\Models\Menu;
use App\Models\Pengaturan;
use App\Models\Tautan;
use Illuminate\Support\Facades\Cache;

/**
 * Cache data publik yang jarang berubah dan dibaca di (hampir) setiap halaman.
 *
 * Aturan (Fase 6.2):
 * - satu key = satu isi query; semua pembaca memakai method di sini, jangan memakai Cache::remember
 *   dengan key yang sama di tempat lain (pernah terjadi: dua query berbeda berbagi key `menu_all`);
 * - TTL seragam 1 jam sebagai jaring pengaman; pembatalan utama dilakukan seketika oleh observer model
 *   (lihat registerInvalidation) setiap kali data disimpan, dihapus, atau dipulihkan lewat Eloquent;
 * - hanya data yang tidak harus real-time. Penghitung tampilan, portofolio, blog, dan data berfilter
 *   `is_active` sengaja TIDAK di-cache di sini;
 * - perubahan yang melewati Eloquent (query builder massal, SQL langsung, seeder) tidak memicu observer,
 *   jadi panggil PublicCache::flush() setelahnya atau tunggu TTL habis.
 */
class PublicCache
{
    public const TTL = 3600;

    /** key => model yang mempengaruhinya */
    private const MODEL_KEYS = [
        Pengaturan::class => ['pengaturan_first'],
        Menu::class => ['menu_all'],
        Tautan::class => ['tautan_all'],
        Layanan::class => ['layanan_all', 'layanan_home'],
        Banner::class => ['banner_all'],
        Kalender::class => ['kalender_all'],
    ];

    public static function pengaturan()
    {
        return Cache::remember('pengaturan_first', self::TTL, fn () => Pengaturan::first());
    }

    public static function menu()
    {
        return Cache::remember('menu_all', self::TTL, fn () => Menu::orderBy('created_at')->orderBy('id')->get());
    }

    public static function tautan()
    {
        return Cache::remember('tautan_all', self::TTL, fn () => Tautan::all());
    }

    /** Daftar layanan terurut judul (footer). */
    public static function layananFooter()
    {
        return Cache::remember('layanan_all', self::TTL, fn () => Layanan::orderBy('title')->get());
    }

    /** Daftar layanan urutan bawaan (halaman depan). */
    public static function layanan()
    {
        return Cache::remember('layanan_home', self::TTL, fn () => Layanan::all());
    }

    public static function banner()
    {
        return Cache::remember('banner_all', self::TTL, fn () => Banner::all());
    }

    public static function kalender()
    {
        return Cache::remember('kalender_all', self::TTL, fn () => Kalender::all());
    }

    public static function keysFor(string $modelClass): array
    {
        return self::MODEL_KEYS[$modelClass] ?? [];
    }

    public static function forgetFor(string $modelClass): void
    {
        foreach (self::keysFor($modelClass) as $key) {
            Cache::forget($key);
        }
    }

    public static function flush(): void
    {
        foreach (self::MODEL_KEYS as $keys) {
            foreach ($keys as $key) {
                Cache::forget($key);
            }
        }
    }

    /** Pasang pembatalan otomatis pada semua model yang datanya di-cache. */
    public static function registerInvalidation(): void
    {
        foreach (array_keys(self::MODEL_KEYS) as $class) {
            $forget = fn () => self::forgetFor($class);

            $class::saved($forget);
            $class::deleted($forget);

            if (method_exists($class, 'restored')) {
                $class::restored($forget);
            }
        }
    }
}

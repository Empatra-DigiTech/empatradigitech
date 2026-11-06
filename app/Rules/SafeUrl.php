<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Tautan yang aman dirender ke atribut href: hanya http, https, mailto, tel,
 * alamat internal yang diawali "/" atau anchor "#". Menolak javascript:, data:, vbscript:, dll,
 * alamat tanpa skema ("www.contoh.com" akan menjadi tautan relatif yang rusak), serta
 * trik seperti "//host", "/\host", spasi/karakter kontrol di dalam alamat.
 */
class SafeUrl implements ValidationRule
{
    private const SCHEMES = ['http', 'https', 'mailto', 'tel'];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return;
        }

        if (! is_string($value) || ! self::isSafe($value)) {
            $fail('Kolom :attribute harus berupa tautan yang valid (diawali http://, https://, mailto:, tel: atau / untuk alamat internal).');
        }
    }

    public static function isSafe(string $url): bool
    {
        if ($url !== trim($url) || $url === '') {
            return false;
        }

        // Spasi, karakter kontrol, dan backslash dipakai untuk menyamarkan skema/host.
        if (preg_match('/[\x00-\x20\x7F-\x9F\\\\]/u', $url) === 1) {
            return false;
        }

        if (preg_match('/^([a-z][a-z0-9+.\-]*):(.*)$/is', $url, $m) === 1) {
            $scheme = strtolower($m[1]);
            if (! in_array($scheme, self::SCHEMES, true)) {
                return false;
            }

            if ($scheme === 'http' || $scheme === 'https') {
                $host = parse_url($url, PHP_URL_HOST);

                // parse_url() mengembalikan false/null untuk "http://"; filled(false) salah mengira itu terisi.
                return is_string($host) && $host !== '';
            }

            return $m[2] !== '';
        }

        if (str_starts_with($url, '#')) {
            return true;
        }

        // Alamat internal: "/halaman" boleh, "//host" (protocol-relative) tidak.
        return str_starts_with($url, '/') && ! str_starts_with($url, '//');
    }
}

<?php

namespace App\Support;

/**
 * reCAPTCHA v2 (paket anhskohbo/no-captcha). Aktif hanya bila kedua kunci terisi di .env:
 * NOCAPTCHA_SITEKEY dan NOCAPTCHA_SECRET. Tanpa kunci, captcha mati dan form berjalan biasa.
 */
class Captcha
{
    public static function enabled(): bool
    {
        return filled(config('captcha.secret')) && filled(config('captcha.sitekey'));
    }
}

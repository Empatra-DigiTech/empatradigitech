<?php

namespace App\Helpers;

use App\Exceptions\UserFacingException;
use Throwable;

class ErrorHelper
{
    public const GENERIC = 'Terjadi kesalahan pada server. Silakan coba lagi atau hubungi administrator.';

    /** Pesan yang boleh dilihat pengguna: pesan asli hanya untuk UserFacingException. */
    public static function userMessage(Throwable $e): string
    {
        return $e instanceof UserFacingException ? $e->getMessage() : self::GENERIC;
    }
}

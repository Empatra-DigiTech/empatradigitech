<?php

namespace App\Exceptions;

use Exception;

/**
 * Exception yang pesannya AMAN ditampilkan ke pengguna (mis. "Data tidak ditemukan",
 * "Format file tidak diperbolehkan"). Exception lain (query error, path file, dll.)
 * hanya dicatat di log dan pengguna melihat pesan umum — lihat ErrorHelper.
 */
class UserFacingException extends Exception
{
}

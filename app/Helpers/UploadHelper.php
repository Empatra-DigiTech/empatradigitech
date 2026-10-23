<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Storage;

class UploadHelper
{
    /** Batas default 5 MB. Pemanggil yang butuh lebih besar (video, dokumen) wajib menyebutkannya. */
    public const DEFAULT_MAX_SIZE = 5 * 1024 * 1024;

    /**
     * Daftar MIME asli (dibaca dari ISI file, bukan nama file) yang sah untuk tiap ekstensi.
     * Ekstensi yang tidak ada di sini otomatis ditolak.
     */
    private const MIME_MAP = [
        'jpg' => ['image/jpeg'],
        'jpeg' => ['image/jpeg'],
        'png' => ['image/png'],
        'gif' => ['image/gif'],
        'webp' => ['image/webp'],
        'pdf' => ['application/pdf'],
        'mp4' => ['video/mp4'],
        'mkv' => ['video/x-matroska', 'video/webm'],
        'avi' => ['video/x-msvideo', 'video/avi', 'video/vnd.avi'],
        'wmv' => ['video/x-ms-wmv', 'video/x-ms-asf', 'application/vnd.ms-asf'],
        'zip' => ['application/zip', 'application/x-zip-compressed'],
        'doc' => ['application/msword', 'application/vnd.ms-office', 'application/x-ole-storage', 'application/CDFV2'],
        'xls' => ['application/vnd.ms-excel', 'application/vnd.ms-office', 'application/x-ole-storage', 'application/CDFV2'],
        'ppt' => ['application/vnd.ms-powerpoint', 'application/vnd.ms-office', 'application/x-ole-storage', 'application/CDFV2'],
        'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/x-zip-compressed'],
        'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip', 'application/x-zip-compressed'],
        'pptx' => ['application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/zip', 'application/x-zip-compressed'],
    ];

    /**
     * @param array $valid_ext whitelist ekstensi (WAJIB, tidak boleh kosong)
     * @param int   $max_size  batas ukuran dalam byte
     */
    public static function upload_file($file, $last_folder, $valid_ext = [], $max_size = self::DEFAULT_MAX_SIZE)
    {
        try {
            if (empty($valid_ext)) {
                // Kesalahan programmer, bukan kesalahan pengguna: catat, jangan bocorkan detail.
                Log::error('UploadHelper dipanggil tanpa whitelist ekstensi', ['folder' => $last_folder]);
                return ['IsError' => true, 'Message' => 'Upload file tidak dapat diproses.'];
            }

            $valid_ext = array_map('strtolower', $valid_ext);
            $ext = strtolower((string) $file->getClientOriginalExtension());

            if ($file->getSize() > $max_size) {
                $mb = round($max_size / 1024 / 1024, 1);
                return ['IsError' => true, 'Message' => "Maksimal ukuran file adalah {$mb} MB"];
            }

            if (! in_array($ext, $valid_ext, true)) {
                return ['IsError' => true, 'Message' => 'Format file diperbolehkan yaitu ' . implode(' , ', $valid_ext)];
            }

            // Cocokkan isi file dengan ekstensi (mencegah file .php/.html disamarkan jadi .png).
            $mime = (string) $file->getMimeType();
            if (! in_array($mime, self::MIME_MAP[$ext] ?? [], true)) {
                return ['IsError' => true, 'Message' => 'Isi file tidak sesuai dengan ekstensinya'];
            }

            $name = Str::random(40) . '.' . $ext;
            Storage::putFileAs('public/' . $last_folder . '/', $file, $name);

            return [
                'IsError' => false,
                'Message' => 'Berhasil mengupload file',
                'Path' => $last_folder . '/' . $name,
            ];
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return ['IsError' => true, 'Message' => ErrorHelper::GENERIC];
        }
    }
}

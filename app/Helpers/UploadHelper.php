<?php

namespace App\Helpers;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Storage;

class UploadHelper
{
    /** Batas default 5 MB. Pemanggil yang butuh lebih besar (video, dokumen) wajib menyebutkannya. */
    public const DEFAULT_MAX_SIZE = 5 * 1024 * 1024;

    /**
     * Kebijakan optimasi gambar (Fase 6.1), berlaku untuk JPG/JPEG/PNG yang diunggah lewat helper ini:
     * - sisi terpanjang dibatasi 1920 px (cukup untuk layar Full HD dan tampilan 2x pada kolom konten);
     * - JPEG disimpan ulang kualitas 82 (progresif), PNG dikompres maksimal dan transparansinya dipertahankan;
     * - ekstensi/format TIDAK diubah (tanpa konversi WebP) agar data lama dan template tetap kompatibel;
     * - GIF dan WebP tidak disentuh (GIF bisa beranimasi);
     * - berkas asli tidak disimpan: yang tersimpan hanya versi hasil optimasi. Simpan master di luar aplikasi bila perlu;
     * - gambar > 40 megapiksel dilewati (decode GD bisa menghabiskan memori), disimpan apa adanya;
     * - bila optimasi gagal atau hasilnya tidak lebih baik, berkas asli yang disimpan.
     */
    public const IMAGE_MAX_DIMENSION = 1920;
    public const IMAGE_JPEG_QUALITY = 82;
    public const IMAGE_RECOMPRESS_ABOVE = 400 * 1024;
    public const IMAGE_MAX_PIXELS = 40_000_000;

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
            $optimized = self::optimizeImage($file->getRealPath(), $ext);

            try {
                Storage::putFileAs('public/' . $last_folder . '/', $optimized !== null ? new File($optimized) : $file, $name);
            } finally {
                if ($optimized !== null) {
                    @unlink($optimized);
                }
            }

            return [
                'IsError' => false,
                'Message' => 'Berhasil mengupload file',
                'Path' => $last_folder . '/' . $name,
                'Optimized' => $optimized !== null,
            ];
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return ['IsError' => true, 'Message' => ErrorHelper::GENERIC];
        }
    }

    /**
     * Perkecil/kompres gambar raster sesuai kebijakan di atas.
     *
     * @return string|null path berkas sementara hasil optimasi (pemanggil wajib menghapusnya),
     *                     atau null bila berkas asli sebaiknya dipakai apa adanya.
     */
    public static function optimizeImage(?string $path, string $ext): ?string
    {
        $ext = strtolower($ext);
        if (! $path || ! is_file($path) || ! in_array($ext, ['jpg', 'jpeg', 'png'], true) || ! function_exists('imagecreatetruecolor')) {
            return null;
        }

        $out = null;
        $src = null;
        $dst = null;

        try {
            $info = @getimagesize($path);
            if (! $info) {
                return null;
            }

            [$w, $h] = $info;
            if ($w < 1 || $h < 1 || $w * $h > self::IMAGE_MAX_PIXELS) {
                return null;
            }

            $originalSize = filesize($path);
            $tooLarge = max($w, $h) > self::IMAGE_MAX_DIMENSION;
            $isJpeg = $ext !== 'png';

            // Orientasi EXIF: GD membuang EXIF saat simpan ulang, jadi putar dulu agar foto tidak miring.
            $rotate = 0;
            if ($isJpeg && function_exists('exif_read_data')) {
                $exif = @exif_read_data($path);
                $rotate = match ((int) ($exif['Orientation'] ?? 1)) {
                    3 => 180,
                    6 => -90,
                    8 => 90,
                    default => 0,
                };
            }

            if (! $tooLarge && ! $rotate && $originalSize <= self::IMAGE_RECOMPRESS_ABOVE) {
                return null;
            }

            $src = $isJpeg ? @imagecreatefromjpeg($path) : @imagecreatefrompng($path);
            if (! $src) {
                return null;
            }

            $scale = $tooLarge ? self::IMAGE_MAX_DIMENSION / max($w, $h) : 1.0;
            $nw = max(1, (int) round($w * $scale));
            $nh = max(1, (int) round($h * $scale));

            $dst = imagecreatetruecolor($nw, $nh);
            if (! $isJpeg) {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
                imagefill($dst, 0, 0, imagecolorallocatealpha($dst, 0, 0, 0, 127));
            }
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
            imagedestroy($src);
            $src = null;

            if ($rotate) {
                $rotated = imagerotate($dst, $rotate, 0);
                if ($rotated) {
                    imagedestroy($dst);
                    $dst = $rotated;
                }
            }

            $out = tempnam(sys_get_temp_dir(), 'img');
            if ($isJpeg) {
                imageinterlace($dst, true);
                $ok = imagejpeg($dst, $out, self::IMAGE_JPEG_QUALITY);
            } else {
                imagesavealpha($dst, true);
                $ok = imagepng($dst, $out, 9);
            }
            imagedestroy($dst);
            $dst = null;

            if (! $ok || ! is_file($out) || filesize($out) === 0) {
                @unlink($out);
                return null;
            }

            // Dimensi dikecilkan / dirotasi: pakai hasilnya. Selain itu hanya bila benar-benar lebih kecil.
            if (! $tooLarge && ! $rotate && filesize($out) >= $originalSize) {
                @unlink($out);
                return null;
            }

            return $out;
        } catch (\Throwable $e) {
            Log::warning('Optimasi gambar gagal, berkas asli dipakai: ' . $e->getMessage());
            if ($src) {
                imagedestroy($src);
            }
            if ($dst) {
                imagedestroy($dst);
            }
            if ($out && is_file($out)) {
                @unlink($out);
            }
            return null;
        }
    }
}

<?php

namespace Tests\Feature;

use App\Helpers\UploadHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/** Fase 6.1: optimasi gambar saat upload (resize, kompres, orientasi EXIF, transparansi). */
class ImageOptimizationTest extends TestCase
{
    private array $tmp = [];

    protected function tearDown(): void
    {
        foreach ($this->tmp as $f) {
            @unlink($f);
        }
        parent::tearDown();
    }

    private function makeFile(string $type, int $w, int $h, bool $noise = true, ?callable $paint = null, string $name = 'x'): UploadedFile
    {
        $im = imagecreatetruecolor($w, $h);
        if ($type === 'png') {
            imagealphablending($im, false);
            imagesavealpha($im, true);
            imagefill($im, 0, 0, imagecolorallocatealpha($im, 0, 0, 0, 127));
        } else {
            imagefill($im, 0, 0, imagecolorallocate($im, 30, 90, 160));
        }
        if ($noise) {
            mt_srand(7);
            for ($i = 0; $i < 4000; $i++) {
                imagefilledrectangle($im, $x = mt_rand(0, $w - 1), $y = mt_rand(0, $h - 1), $x + mt_rand(5, 80), $y + mt_rand(5, 80),
                    imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255)));
            }
        }
        if ($paint) {
            $paint($im);
        }
        $path = tempnam(sys_get_temp_dir(), 'imgtest');
        $this->tmp[] = $path;
        match ($type) {
            'png' => imagepng($im, $path, 1),
            'gif' => imagegif($im, $path),
            default => imagejpeg($im, $path, 100),
        };
        imagedestroy($im);

        return new UploadedFile($path, "{$name}.{$type}", match ($type) {
            'png' => 'image/png', 'gif' => 'image/gif', default => 'image/jpeg',
        }, null, true);
    }

    private function stored(array $r): string
    {
        $this->assertFalse($r['IsError'], $r['Message'] ?? '');

        return Storage::get('public/' . $r['Path']);
    }

    public function test_jpeg_besar_diperkecil_dan_rasio_dipertahankan(): void
    {
        Storage::fake();
        $file = $this->makeFile('jpg', 4000, 2000);
        $before = filesize($file->getRealPath());

        $r = UploadHelper::upload_file($file, 'uji', ['jpg']);
        $bytes = $this->stored($r);
        [$w, $h, $type] = getimagesizefromstring($bytes);

        $this->assertTrue($r['Optimized']);
        $this->assertSame(UploadHelper::IMAGE_MAX_DIMENSION, $w);
        $this->assertSame(960, $h);
        $this->assertSame(IMAGETYPE_JPEG, $type);
        $this->assertLessThan($before, strlen($bytes));
    }

    public function test_gambar_kecil_disimpan_apa_adanya(): void
    {
        Storage::fake();
        $file = $this->makeFile('jpg', 300, 200, false);
        $original = file_get_contents($file->getRealPath());

        $r = UploadHelper::upload_file($file, 'uji', ['jpg']);

        $this->assertFalse($r['Optimized']);
        $this->assertSame($original, $this->stored($r));
    }

    public function test_png_transparan_tetap_transparan_setelah_diperkecil(): void
    {
        Storage::fake();
        $file = $this->makeFile('png', 2500, 1000, false, function ($im) {
            imagefilledellipse($im, 1250, 500, 800, 800, imagecolorallocate($im, 200, 0, 0));
        });

        $r = UploadHelper::upload_file($file, 'uji', ['png']);
        $bytes = $this->stored($r);
        [$w, $h, $type] = getimagesizefromstring($bytes);

        $this->assertSame(IMAGETYPE_PNG, $type);
        $this->assertSame(1920, $w);
        $this->assertSame(768, $h);

        $im = imagecreatefromstring($bytes);
        $corner = imagecolorsforindex($im, imagecolorat($im, 2, 2));
        $center = imagecolorsforindex($im, imagecolorat($im, 960, 384));
        $this->assertSame(127, $corner['alpha'], 'sudut harus tetap transparan');
        $this->assertSame(0, $center['alpha']);
        $this->assertGreaterThan(150, $center['red']);
    }

    public function test_gif_tidak_disentuh(): void
    {
        Storage::fake();
        $file = $this->makeFile('gif', 2500, 1200);
        $original = file_get_contents($file->getRealPath());

        $r = UploadHelper::upload_file($file, 'uji', ['gif'], 20 * 1024 * 1024);

        $this->assertFalse($r['Optimized']);
        $this->assertSame($original, $this->stored($r));
    }

    public function test_orientasi_exif_dikoreksi_agar_foto_tidak_miring(): void
    {
        Storage::fake();
        // 300x200: kiri merah, kanan biru. EXIF orientation 6 = harus diputar 90° searah jarum jam
        // agar tampil benar, hasilnya 200x300 dengan merah di ATAS dan biru di BAWAH.
        $file = $this->makeFile('jpg', 300, 200, false, function ($im) {
            imagefilledrectangle($im, 0, 0, 149, 199, imagecolorallocate($im, 255, 0, 0));
            imagefilledrectangle($im, 150, 0, 299, 199, imagecolorallocate($im, 0, 0, 255));
        });
        $jpeg = file_get_contents($file->getRealPath());
        $tiff = "II*\x00\x08\x00\x00\x00" . "\x01\x00" . "\x12\x01\x03\x00\x01\x00\x00\x00\x06\x00\x00\x00" . "\x00\x00\x00\x00";
        $app1 = "Exif\x00\x00" . $tiff;
        $withExif = substr($jpeg, 0, 2) . "\xFF\xE1" . pack('n', strlen($app1) + 2) . $app1 . substr($jpeg, 2);
        file_put_contents($file->getRealPath(), $withExif);

        $r = UploadHelper::upload_file($file, 'uji', ['jpg']);
        $im = imagecreatefromstring($this->stored($r));

        $this->assertSame(200, imagesx($im));
        $this->assertSame(300, imagesy($im));
        $top = imagecolorsforindex($im, imagecolorat($im, 100, 10));
        $bottom = imagecolorsforindex($im, imagecolorat($im, 100, 290));
        $this->assertGreaterThan(200, $top['red']);
        $this->assertGreaterThan(200, $bottom['blue']);
    }

    public function test_berkas_bukan_gambar_atau_rusak_mengembalikan_null_tanpa_error(): void
    {
        $f = tempnam(sys_get_temp_dir(), 'bad');
        $this->tmp[] = $f;
        file_put_contents($f, str_repeat('bukan gambar', 100000));

        $this->assertNull(UploadHelper::optimizeImage($f, 'jpg'));
        $this->assertNull(UploadHelper::optimizeImage($f, 'png'));
        $this->assertNull(UploadHelper::optimizeImage('/tidak/ada.jpg', 'jpg'));
        $this->assertNull(UploadHelper::optimizeImage($f, 'pdf'));
    }
}

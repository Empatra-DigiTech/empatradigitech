<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Pemeriksaan statis view admin.
 *
 * Bug lama: `onsubmit="confirm('...')"` TANPA `return` -> dialog muncul tetapi tombol Batal
 * tetap mengirim form. Handler yang benar: `onsubmit="return confirm('...')"`.
 */
class ConfirmOnSubmitTest extends TestCase
{
    private function views(): array
    {
        $files = [];
        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(__DIR__ . '/../../resources/views'));
        foreach ($it as $f) {
            if ($f->isFile() && str_ends_with($f->getFilename(), '.blade.php')) {
                $files[] = $f->getPathname();
            }
        }

        return $files;
    }

    public function test_onsubmit_confirm_selalu_memakai_return(): void
    {
        $bad = [];
        foreach ($this->views() as $path) {
            // onsubmit / onclick yang memanggil confirm( tanpa `return` di depannya.
            if (preg_match('/\bon(submit|click)\s*=\s*["\'](?!\s*return\s)[^"\']*\bconfirm\s*\(/i', file_get_contents($path))) {
                $bad[] = str_replace(dirname(__DIR__, 2) . '/', '', $path);
            }
        }

        $this->assertSame([], $bad, "Handler confirm() tanpa return (Batal tetap mengirim form):\n" . implode("\n", $bad));
    }

    public function test_masih_ada_form_dengan_konfirmasi_yang_benar(): void
    {
        // Menjaga agar test di atas tidak lulus semu karena pola tidak pernah cocok.
        $count = 0;
        foreach ($this->views() as $path) {
            $count += preg_match_all('/onsubmit\s*=\s*"return confirm\(/', file_get_contents($path));
        }

        $this->assertGreaterThanOrEqual(35, $count);
    }

    public function test_semua_form_tambah_dan_ubah_meminta_konfirmasi(): void
    {
        $missing = [];
        $base = __DIR__ . '/../../resources/views/patra/pages';
        foreach (glob($base . '/*/{create,edit}.blade.php', GLOB_BRACE) as $path) {
            $src = file_get_contents($path);
            if (str_contains($src, '<form') && ! preg_match('/confirm\s*\(/', $src)) {
                $missing[] = basename(dirname($path)) . '/' . basename($path);
            }
        }

        $this->assertSame([], $missing, "Form tanpa konfirmasi:\n" . implode("\n", $missing));
    }
}

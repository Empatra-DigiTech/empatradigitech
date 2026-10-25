<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Fase 7.1: nama file, namespace, dan class di app/ harus cocok PERSIS (huruf besar/kecil) dengan aturan PSR-4.
 * Windows/macOS tidak peduli kapitalisasi, Linux (server produksi, CI) peduli: class yang file-nya salah huruf
 * hanya gagal di-autoload di sana.
 */
class AutoloadNamingTest extends TestCase
{
    public function test_setiap_class_di_app_cocok_persis_dengan_path_psr4(): void
    {
        $root = dirname(__DIR__, 2) . '/app';
        $bad = [];

        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS));
        foreach ($it as $f) {
            if ($f->getExtension() !== 'php') {
                continue;
            }

            $src = file_get_contents($f->getPathname());
            if (! preg_match('/^namespace\s+([^;]+);/m', $src, $ns) || ! preg_match('/^(?:abstract\s+|final\s+)?(?:class|interface|trait|enum)\s+(\w+)/m', $src, $cls)) {
                continue;
            }

            $rel = str_replace('\\', '/', substr($f->getPathname(), strlen($root) + 1));
            $expected = str_replace('\\', '/', preg_replace('/^App\\\\?/', '', $ns[1]));
            $expected = ltrim(($expected === '' ? '' : $expected . '/') . $cls[1] . '.php', '/');

            if ($rel !== $expected) {
                $bad[] = "app/{$rel} mendeklarasikan {$ns[1]}\\{$cls[1]} (harus app/{$expected})";
            }
        }

        $this->assertSame([], $bad, "Nama tidak sesuai PSR-4:\n" . implode("\n", $bad));
    }

    public function test_semua_model_bisa_di_autoload(): void
    {
        $root = dirname(__DIR__, 2) . '/app/Models';
        $missing = [];
        foreach (glob($root . '/*.php') as $file) {
            $class = 'App\\Models\\' . basename($file, '.php');
            if (! class_exists($class)) {
                $missing[] = $class;
            }
        }

        $this->assertSame([], $missing, 'Tidak bisa di-autoload: ' . implode(', ', $missing));
    }
}

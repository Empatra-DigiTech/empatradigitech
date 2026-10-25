<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/** Fase 7.4: error aplikasi biasa dicatat sebagai error; emergency/alert/critical dicadangkan untuk kondisi darurat sungguhan. */
class LogLevelTest extends TestCase
{
    public function test_tidak_ada_log_emergency_alert_critical_di_kode_aplikasi(): void
    {
        $root = dirname(__DIR__, 2) . '/app';
        $hits = [];
        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS));
        foreach ($it as $f) {
            if ($f->getExtension() === 'php' && preg_match('/Log::(emergency|alert|critical)\(/', file_get_contents($f->getPathname()))) {
                $hits[] = str_replace('\\', '/', substr($f->getPathname(), strlen($root) + 1));
            }
        }

        $this->assertSame([], $hits, 'Gunakan Log::error untuk kegagalan biasa: ' . implode(', ', $hits));
    }
}

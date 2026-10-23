<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/** Pemeriksaan statis: form hapus di view admin tidak boleh memakai confirm() bawaan browser. */
class ConfirmOnSubmitTest extends TestCase
{
    public function test_tidak_ada_onsubmit_confirm_bawaan_browser(): void
    {
        if (! getenv('RUN_PENDING')) {
            $this->markTestSkipped('Menunggu perbaikan Fase 5 (modal konfirmasi seragam).');
        }

        $hits = [];
        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(__DIR__ . '/../../resources/views/patra'));
        foreach ($it as $f) {
            if ($f->isFile() && str_ends_with($f->getFilename(), '.blade.php')
                && str_contains(file_get_contents($f->getPathname()), 'onsubmit="return confirm(')) {
                $hits[] = $f->getPathname();
            }
        }
        $this->assertSame([], $hits, count($hits) . ' view masih memakai confirm() bawaan.');
    }
}

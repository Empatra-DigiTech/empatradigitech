<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/** Tombol/tautan admin yang isinya hanya ikon wajib punya nama aksesibel (aria-label). */
class IconButtonAccessibilityTest extends TestCase
{
    public function test_tombol_ikon_di_admin_punya_aria_label(): void
    {
        $bad = [];
        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(__DIR__ . '/../../resources/views/patra'));
        foreach ($it as $f) {
            if (! $f->isFile() || ! str_ends_with($f->getFilename(), '.blade.php')) {
                continue;
            }
            $src = file_get_contents($f->getPathname());
            // (?<=-)> membolehkan "->" di dalam {{ $row->id }} pada atribut.
            preg_match_all('/<(a|button)\b((?:[^>]|(?<=-)>)*)>\s*(?:<i\b[^>]*>\s*<\/i>\s*)+<\/\1>/s', $src, $m, PREG_SET_ORDER);
            foreach ($m as $hit) {
                if (! str_contains($hit[2], 'aria-label')) {
                    $bad[] = basename(dirname($f->getPathname())) . '/' . $f->getFilename() . ' :: ' . preg_replace('/\s+/', ' ', substr($hit[0], 0, 110));
                }
            }
        }

        $this->assertSame([], $bad, "Tombol ikon tanpa aria-label:\n" . implode("\n", $bad));
    }

    public function test_indikator_fokus_keyboard_tersedia_dan_dimuat_setelah_style_bawaan(): void
    {
        $head = file_get_contents(__DIR__ . '/../../resources/views/patra/layouts/head.blade.php');
        $this->assertGreaterThan(strpos($head, 'patra/assets/css/style.css'), strpos($head, 'patra/a11y.css'));

        $css = file_get_contents(__DIR__ . '/../../public/assets/css/patra/a11y.css');
        $this->assertStringContainsString(':focus-visible', $css);
    }

    public function test_modal_tidak_memakai_tabindex_tidak_valid(): void
    {
        $bad = [];
        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(__DIR__ . '/../../resources/views'));
        foreach ($it as $f) {
            if ($f->isFile() && str_contains(file_get_contents($f->getPathname()), 'tabindex="false"')) {
                $bad[] = $f->getPathname();
            }
        }

        $this->assertSame([], $bad);
    }
}

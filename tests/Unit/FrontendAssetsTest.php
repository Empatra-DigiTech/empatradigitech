<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/** Pagar konsistensi aset front-end (Fase 5): token, font, navbar, CSS inline. */
class FrontendAssetsTest extends TestCase
{
    private function root(string $p = ''): string
    {
        return dirname(__DIR__, 2) . '/' . ltrim($p, '/');
    }

    private function read(string $p): string
    {
        return file_get_contents($this->root($p));
    }

    public function test_token_warna_merek_hanya_didefinisikan_di_tokens_css(): void
    {
        $files = array_merge(
            glob($this->root('public/assets/css/*.css')),
            glob($this->root('public/assets/css/home/*.css')),
            glob($this->root('public/assets/css/home/*/*.css')),
            glob($this->root('public/assets/css/galery/*.css')),
            glob($this->root('public/assets/css/portfolio/*.css')),
            glob($this->root('public/assets/css/profil/*.css')),
            glob($this->root('resources/css/*.css'))
        );

        $dups = [];
        foreach ($files as $f) {
            $name = basename($f);
            if (in_array($name, ['tokens.css'], true) || str_contains($f, 'swiper') || str_contains($name, 'kaiadmin')
                || str_contains($name, 'bootstrap') || str_contains($name, 'plugins') || str_contains($name, 'fonts')) {
                continue;
            }
            if (preg_match('/^\s*--(primary|secondary|accent)(-dark|-light)?\s*:/m', file_get_contents($f))) {
                $dups[] = str_replace($this->root(), '', $f);
            }
        }

        $this->assertSame([], $dups, "Token merek didefinisikan ulang (harus hanya di tokens.css):\n" . implode("\n", $dups));
    }

    public function test_tokens_css_dimuat_sebelum_css_lain_di_layout_publik(): void
    {
        $head = $this->read('resources/views/home/layouts/head.blade.php');

        $this->assertNotFalse(strpos($head, 'assets/css/tokens.css'));
        $this->assertLessThan(strpos($head, 'assets/css/main.css'), strpos($head, 'assets/css/tokens.css'));
    }

    public function test_warna_inti_merek_sesuai(): void
    {
        $css = $this->read('public/assets/css/tokens.css');

        $this->assertMatchesRegularExpression('/--primary:\s*#b02e36;/i', $css);
        $this->assertMatchesRegularExpression('/--secondary:\s*#0f3c5d;/i', $css);
    }

    public function test_layout_publik_hanya_memuat_font_inter(): void
    {
        $head = $this->read('resources/views/home/layouts/head.blade.php');

        preg_match_all('#fonts\.googleapis\.com/css2\?([^"\']+)#', $head, $m);
        $families = [];
        foreach ($m[1] as $q) {
            preg_match_all('/family=([A-Za-z+ ]+)/', $q, $f);
            $families = array_merge($families, $f[1]);
        }

        $this->assertSame(['Inter'], array_values(array_unique($families)));
    }

    public function test_navbar_hero_gelap_memakai_class_halaman_bukan_has(): void
    {
        $this->assertStringNotContainsString(':has(', $this->read('public/assets/css/home/blog/blog.css'));
        $this->assertStringNotContainsString(':has(', $this->read('public/assets/css/galery/style.css'));
        $this->assertStringContainsString('.page-dark-hero .site-navbar', $this->read('resources/css/home.css'));
        $this->assertStringContainsString('<body class="@yield(\'body_class\')">', $this->read('resources/views/home/layouts/master.blade.php'));

        foreach (['blog', 'galeri'] as $page) {
            $this->assertStringContainsString("@section('body_class', 'page-dark-hero')", $this->read("resources/views/home/pages/{$page}/index.blade.php"));
        }
    }

    public function test_gradient_blog_galeri_merah_ke_navy(): void
    {
        foreach (['public/assets/css/home/blog/blog.css' => 'blog', 'public/assets/css/galery/style.css' => 'gl'] as $f => $p) {
            $css = $this->read($f);
            $this->assertStringContainsString("--{$p}-primary: var(--secondary", $css);   // navy
            $this->assertStringContainsString("--{$p}-secondary: var(--primary", $css);   // merah
            $this->assertStringContainsString("linear-gradient(135deg, var(--{$p}-secondary) 0%, var(--{$p}-primary) 100%)", $css);
        }
    }

    public function test_css_invoice_dipindah_dari_blade_ke_aset_vite(): void
    {
        $vite = $this->read('vite.config.js');
        foreach (['create', 'edit', 'show'] as $v) {
            $blade = $this->read("resources/views/patra/pages/invoice/{$v}.blade.php");
            $this->assertStringNotContainsString('<style', $blade, "{$v}.blade.php masih punya <style> inline");
            $this->assertStringContainsString("@vite('resources/css/patra/invoice-{$v}.css')", $blade);
            $this->assertFileExists($this->root("resources/css/patra/invoice-{$v}.css"));
            $this->assertStringContainsString("resources/css/patra/invoice-{$v}.css", $vite);
        }
    }

    public function test_favicon_admin_menunjuk_ke_file_yang_ada(): void
    {
        $head = $this->read('resources/views/patra/layouts/head.blade.php');
        preg_match('#rel="shortcut icon" href="\{\{URL::to\(\'/\'\)\}\}/([^"]+)"#', $head, $m);

        $this->assertNotEmpty($m);
        $this->assertFileExists($this->root('public/' . $m[1]));
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;

/** Semua halaman publik & index admin harus terbuka tanpa error 500. */
class SmokeTest extends TestCase
{
    public function test_halaman_publik_tidak_error(): void
    {
        foreach (['/', '/blog', '/galeri', '/inovasi', '/portofolio', '/informasi', '/kontak', '/layanan'] as $url) {
            $status = $this->get($url)->getStatusCode();
            $this->assertContains($status, [200, 301, 302, 404], "{$url} -> {$status}");
            $this->assertNotSame(500, $status, $url);
        }
    }

    public function test_beranda_ok(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_index_admin_superadmin_tidak_error(): void
    {
        $sa = $this->superAdmin();
        foreach ([
            '/patra', '/patra/menu', '/patra/banner', '/patra/portofolio', '/patra/galeri', '/patra/informasi',
            '/patra/blog', '/patra/faq', '/patra/testimoni', '/patra/client-logo', '/patra/layanan', '/patra/paket',
            '/patra/inovasi', '/patra/tautan', '/patra/calculator-service', '/patra/calculator-feature',
            '/patra/kontak', '/patra/invoice', '/patra/vm', '/patra/team', '/patra/finance',
            '/patra/finance-category', '/patra/users', '/patra/pengaturan', '/patra/profile',
        ] as $url) {
            $status = $this->actingAs($sa)->get($url)->getStatusCode();
            $this->assertSame(200, $status, "{$url} -> {$status}");
        }
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Matriks akses via URL langsung. Peta "gated" di bawah mencerminkan routes/web.php
 * SAAT INI; modul yang belum dibatasi dicatat di test pending (Fase 3).
 */
class RoleAccessTest extends TestCase
{
    /** URL index => peran yang BOLEH (SuperAdmin selalu boleh). */
    private const GATED_ADMIN_UP = [
        '/patra/inovasi', '/patra/client-logo', '/patra/calculator-service', '/patra/calculator-feature',
        '/patra/menu', '/patra/banner', '/patra/galeri', '/patra/informasi',
        '/patra/layanan', '/patra/paket', '/patra/tautan', '/patra/kontak',
        '/patra/invoice', '/patra/vm', '/patra/team',
    ];

    private const SUPERADMIN_ONLY = [
        '/patra/finance', '/patra/finance-category', '/patra/users', '/patra/pengaturan',
    ];

    /** Modul konten yang memang boleh dikelola Moderator (keputusan Fase 3). */
    private const MODERATOR_ALLOWED = [
        '/patra/profile', '/patra/portofolio', '/patra/blog', '/patra/faq', '/patra/testimoni',
    ];

    public function test_tamu_diarahkan_ke_login(): void
    {
        foreach (array_merge(self::GATED_ADMIN_UP, self::SUPERADMIN_ONLY) as $url) {
            $this->get($url)->assertRedirect();
        }
    }

    public function test_moderator_dilarang_di_modul_admin(): void
    {
        $mod = $this->moderator();
        foreach (array_merge(self::GATED_ADMIN_UP, self::SUPERADMIN_ONLY) as $url) {
            $this->actingAs($mod)->get($url)->assertForbidden();
        }
    }

    public function test_admin_boleh_modul_admin_tapi_dilarang_modul_superadmin(): void
    {
        $admin = $this->admin();
        foreach (self::GATED_ADMIN_UP as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
        foreach (self::SUPERADMIN_ONLY as $url) {
            $this->actingAs($admin)->get($url)->assertForbidden();
        }
    }

    public function test_superadmin_boleh_semua(): void
    {
        $sa = $this->superAdmin();
        foreach (array_merge(self::GATED_ADMIN_UP, self::SUPERADMIN_ONLY) as $url) {
            $this->actingAs($sa)->get($url)->assertOk();
        }
    }

    public function test_moderator_boleh_membuka_modul_konten_miliknya(): void
    {
        $mod = $this->moderator();
        foreach (self::MODERATOR_ALLOWED as $url) {
            $this->actingAs($mod)->get($url)->assertOk();
        }
    }

    public function test_moderator_sidebar_menampilkan_menu_yang_boleh_diakses(): void
    {
        $html = $this->actingAs($this->moderator())->get('/patra')->getContent();

        foreach (['patra/portofolio', 'patra/blog', 'patra/faq', 'patra/testimoni'] as $path) {
            $this->assertStringContainsString($path, $html, "Menu {$path} harus tampil untuk Moderator");
        }
        foreach (['patra/inovasi', 'patra/client-logo', 'patra/calculator-service', 'patra/finance', 'patra/invoice'] as $path) {
            $this->assertStringNotContainsString($path, $html, "Menu {$path} tidak boleh tampil untuk Moderator");
        }
    }

    public function test_aksi_ubah_data_modul_admin_dilarang_untuk_moderator(): void
    {
        $mod = $this->moderator();
        $this->actingAs($mod)->post('/patra/inovasi', [])->assertForbidden();
        $this->actingAs($mod)->post('/patra/client-logo', [])->assertForbidden();
        $this->actingAs($mod)->delete('/patra/calculator-service/1')->assertForbidden();
    }

    public function test_aksi_ubah_data_dilarang_untuk_moderator(): void
    {
        $mod = $this->moderator();
        $this->actingAs($mod)->post('/patra/finance', [])->assertForbidden();
        $this->actingAs($mod)->delete('/patra/finance/1')->assertForbidden();
        $this->actingAs($mod)->post('/patra/invoice', [])->assertForbidden();
    }
}

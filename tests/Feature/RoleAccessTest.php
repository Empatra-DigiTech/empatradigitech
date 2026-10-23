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
        '/patra/menu', '/patra/banner', '/patra/galeri', '/patra/informasi',
        '/patra/layanan', '/patra/paket', '/patra/tautan', '/patra/kontak',
        '/patra/invoice', '/patra/vm', '/patra/team',
    ];

    private const SUPERADMIN_ONLY = [
        '/patra/finance', '/patra/finance-category', '/patra/users', '/patra/pengaturan',
    ];

    /** Modul yang saat ini bisa dibuka Moderator (target pembatasan Fase 3). */
    private const UNGATED_FOR_MODERATOR = [
        '/patra/profile', '/patra/portofolio', '/patra/inovasi', '/patra/blog', '/patra/faq',
        '/patra/testimoni', '/patra/client-logo', '/patra/calculator-service', '/patra/calculator-feature',
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

    public function test_moderator_bisa_membuka_modul_tanpa_pembatasan_saat_ini(): void
    {
        // Mengunci kondisi BASELINE (temuan Fase 0). Hapus test ini saat Fase 3 selesai.
        $mod = $this->moderator();
        foreach (self::UNGATED_FOR_MODERATOR as $url) {
            $this->assertNotSame(403, $this->actingAs($mod)->get($url)->getStatusCode(), $url);
        }
    }

    public function test_moderator_dibatasi_ke_modul_konten_yang_ditetapkan(): void
    {
        $this->pendingFix('Fase 3 (keputusan hak Moderator)');

        $mod = $this->moderator();
        foreach (['/patra/inovasi', '/patra/client-logo', '/patra/calculator-service'] as $url) {
            $this->actingAs($mod)->get($url)->assertForbidden();
        }
    }

    public function test_aksi_ubah_data_dilarang_untuk_moderator(): void
    {
        $mod = $this->moderator();
        $this->actingAs($mod)->post('/patra/finance', [])->assertForbidden();
        $this->actingAs($mod)->delete('/patra/finance/1')->assertForbidden();
        $this->actingAs($mod)->post('/patra/invoice', [])->assertForbidden();
    }
}

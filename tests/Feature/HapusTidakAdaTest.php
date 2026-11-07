<?php

namespace Tests\Feature;

use Tests\TestCase;

/** C7: menghapus data yang tidak ada harus memberi pesan "tidak ditemukan", bukan pesan server generik. */
class HapusTidakAdaTest extends TestCase
{
    public static function sumber(): array
    {
        return array_map(fn ($s) => [$s], [
            'banner', 'blog', 'calculator-feature', 'calculator-service', 'client-logo', 'faq',
            'finance-category', 'finance', 'galeri', 'informasi', 'inovasi', 'invoice', 'kalender',
            'kontak', 'layanan', 'menu', 'paket', 'portofolio', 'tautan', 'team', 'testimoni',
        ]);
    }

    /** @dataProvider sumber */
    public function test_hapus_data_tidak_ada_memberi_pesan_jelas(string $slug): void
    {
        $this->actingAs($this->superAdmin())->delete("/patra/{$slug}/999999");

        $msg = (string) session('alert.config');
        $this->assertStringNotContainsString('kesalahan pada server', $msg, $slug);
        $this->assertStringContainsString('tidak ditemukan', strtolower($msg), $slug);
    }

    public function test_hapus_user_tidak_ada(): void
    {
        $this->actingAs($this->superAdmin())->delete('/patra/users/999999');

        $msg = (string) session('alert.config');
        $this->assertStringNotContainsString('kesalahan pada server', $msg);
        $this->assertStringContainsString('tidak ditemukan', strtolower($msg));
    }
}

<?php

namespace Tests\Feature;

use App\Models\Layanan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** K2: konten Trix dicetak dengan {!! !!}, jadi renderTrix() wajib membuang script dari konten yang tersimpan. */
class TrixSanitizeTest extends TestCase
{
    use RefreshDatabase;

    private function layananDenganKonten(string $html): Layanan
    {
        $layanan = Layanan::create(['title' => 'Uji', 'image' => 'a.png']);
        $layanan->trixRichText()->create(['field' => 'content', 'content' => $html]);

        return $layanan->fresh();
    }

    public function test_render_trix_membuang_script_dan_atribut_berbahaya(): void
    {
        $layanan = $this->layananDenganKonten(
            '<div><strong>Aman</strong></div><script>alert(1)</script><img src="x" onerror="alert(2)">'
            . '<a href="javascript:alert(3)">klik</a>'
        );

        $out = $layanan->renderTrix('content');

        $this->assertStringContainsString('<strong>Aman</strong>', $out);
        $this->assertStringNotContainsString('<script', $out);
        $this->assertStringNotContainsString('onerror', $out);
        $this->assertStringNotContainsString('javascript:', $out);
    }

    public function test_render_trix_tanpa_konten_mengembalikan_string_kosong(): void
    {
        $layanan = Layanan::create(['title' => 'Kosong', 'image' => 'a.png']);

        $this->assertSame('', $layanan->renderTrix('content'));
    }
}

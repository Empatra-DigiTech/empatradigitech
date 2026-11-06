<?php

namespace Tests\Feature;

use App\Models\CalculatorFeature;
use App\Models\CalculatorService;
use Tests\TestCase;

/** C3: pesan validasi kalkulator harus sampai ke pengguna, bukan "kesalahan server". */
class KalkulatorValidasiTest extends TestCase
{
    public static function target(): array
    {
        return [
            'fitur'   => ['calculator-feature', CalculatorFeature::class, ['nama_fitur' => 'F', 'harga_tambahan' => 10], 'nama_fitur'],
            'layanan' => ['calculator-service', CalculatorService::class, ['nama_layanan' => 'L', 'harga_dasar' => 10, 'harga_per_halaman' => 5], 'nama_layanan'],
        ];
    }

    /** @dataProvider target */
    public function test_pesan_validasi_tampil_bukan_kesalahan_server(string $slug, string $model, array $ok, string $namaField): void
    {
        $sa = $this->superAdmin();
        $awal = $model::count();

        $r = $this->actingAs($sa)->post("/patra/{$slug}", [$namaField => '', 'harga_tambahan' => 'abc', 'harga_dasar' => -1, 'harga_per_halaman' => 'x']);

        $r->assertRedirect(route("patra.{$slug}.create"));
        $html = (string) session('alert.config');
        $this->assertStringNotContainsString('kesalahan pada server', $html);
        $this->assertSame($awal, $model::count());
    }

    /** @dataProvider target */
    public function test_urutan_bukan_angka_ditolak_sebelum_database(string $slug, string $model, array $ok, string $namaField): void
    {
        $awal = $model::count();
        $this->actingAs($this->superAdmin())
            ->post("/patra/{$slug}", $ok + ['urutan' => 'abc'])
            ->assertRedirect(route("patra.{$slug}.create"));

        $this->assertSame($awal, $model::count());
        $this->assertStringNotContainsString('kesalahan pada server', (string) session('alert.config'));
    }

    /** @dataProvider target */
    public function test_data_valid_tersimpan_dan_nama_terlalu_panjang_ditolak(string $slug, string $model, array $ok, string $namaField): void
    {
        $sa = $this->superAdmin();
        $awal = $model::count();

        $this->actingAs($sa)->post("/patra/{$slug}", $ok + ['urutan' => 3])->assertRedirect(route("patra.{$slug}.index"));
        $this->assertSame($awal + 1, $model::count());

        $this->actingAs($sa)->post("/patra/{$slug}", array_merge($ok, [$namaField => str_repeat('a', 300)]));
        $this->assertSame($awal + 1, $model::count());
    }

    /** @dataProvider target */
    public function test_update_tidak_valid_kembali_ke_form_edit_dengan_pesan(string $slug, string $model, array $ok, string $namaField): void
    {
        $sa = $this->superAdmin();
        $row = $model::create($ok + ['urutan' => 0, 'is_active' => true]);

        $this->actingAs($sa)->put("/patra/{$slug}/{$row->id}", [$namaField => ''])
            ->assertRedirect(route("patra.{$slug}.edit", $row->id));

        $this->assertStringNotContainsString('kesalahan pada server', (string) session('alert.config'));
        $this->assertSame($ok[$namaField], $row->fresh()->{$namaField});
    }

    /** @dataProvider target */
    public function test_hapus_data_yang_tidak_ada_memberi_pesan_jelas(string $slug, string $model, array $ok, string $namaField): void
    {
        $this->actingAs($this->superAdmin())->delete("/patra/{$slug}/999")
            ->assertRedirect(route("patra.{$slug}.index"));

        $this->assertStringContainsString('tidak ditemukan', (string) session('alert.config'));
    }
}

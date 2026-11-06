<?php

namespace Tests\Feature;

use App\Models\FinanceCategory;
use App\Models\FinanceTransaction;
use Tests\TestCase;

/**
 * B1: kategori transaksi harus cocok jenis & aktif.
 * B2: hierarki kategori (maks. 2 level, jenis induk = jenis anak, tanpa siklus).
 */
class FinanceKategoriValidasiTest extends TestCase
{
    private function kat(string $type, array $extra = []): FinanceCategory
    {
        return FinanceCategory::create(array_merge(
            ['name' => 'K' . uniqid(), 'type' => $type, 'is_active' => true],
            $extra
        ));
    }

    private function payload(FinanceCategory $k, array $o = []): array
    {
        return array_merge([
            'date' => '2026-10-05', 'description' => 'Uji', 'finance_category_id' => $k->id,
            'type' => $k->type, 'amount' => 1000, 'payment_method' => 'Transfer',
        ], $o);
    }

    private function trx(FinanceCategory $k): FinanceTransaction
    {
        return FinanceTransaction::createWithAutoNumber([
            'date' => '2026-10-01', 'description' => 'Lama', 'finance_category_id' => $k->id,
            'type' => $k->type, 'amount' => 500, 'payment_method' => 'Tunai',
        ]);
    }

    // ---- B1 ----

    public function test_transaksi_dengan_kategori_beda_jenis_ditolak(): void
    {
        $keluar = $this->kat('keluar');

        $this->actingAs($this->superAdmin())
            ->post('/patra/finance', $this->payload($keluar, ['type' => 'masuk']))
            ->assertSessionHas('alert.config');

        $this->assertDatabaseCount('finance_transactions', 0);
    }

    public function test_transaksi_dengan_kategori_nonaktif_ditolak(): void
    {
        $k = $this->kat('masuk', ['is_active' => false]);

        $this->actingAs($this->superAdmin())->post('/patra/finance', $this->payload($k));

        $this->assertDatabaseCount('finance_transactions', 0);
    }

    public function test_transaksi_dengan_kategori_valid_tersimpan(): void
    {
        $k = $this->kat('masuk');

        $this->actingAs($this->superAdmin())->post('/patra/finance', $this->payload($k));

        $this->assertDatabaseCount('finance_transactions', 1);
    }

    public function test_edit_transaksi_boleh_tetap_di_kategori_yang_kini_nonaktif(): void
    {
        $k = $this->kat('masuk');
        $t = $this->trx($k);
        $k->update(['is_active' => false]);

        $this->actingAs($this->superAdmin())
            ->put("/patra/finance/{$t->id}", $this->payload($k, ['description' => 'Diubah']));

        $this->assertDatabaseHas('finance_transactions', ['id' => $t->id, 'description' => 'Diubah']);
    }

    public function test_edit_transaksi_tidak_boleh_pindah_ke_kategori_nonaktif_atau_beda_jenis(): void
    {
        $k = $this->kat('masuk');
        $nonaktif = $this->kat('masuk', ['is_active' => false]);
        $keluar = $this->kat('keluar');
        $t = $this->trx($k);
        $sa = $this->superAdmin();

        $this->actingAs($sa)
            ->put("/patra/finance/{$t->id}", $this->payload($nonaktif, ['description' => 'A']));
        $this->actingAs($sa)
            ->put("/patra/finance/{$t->id}", $this->payload($keluar, ['type' => 'masuk', 'description' => 'B']));

        $this->assertDatabaseHas('finance_transactions', ['id' => $t->id, 'description' => 'Lama']);
    }

    // ---- B2 ----

    private function kirimKategori(array $o = []): void
    {
        $this->actingAs($this->superAdmin())->post('/patra/finance-category', array_merge(
            ['name' => 'Baru', 'type' => 'masuk', 'is_active' => 1], $o
        ));
    }

    public function test_induk_harus_level_teratas(): void
    {
        $induk = $this->kat('masuk');
        $anak = $this->kat('masuk', ['parent_id' => $induk->id]);

        $this->kirimKategori(['parent_id' => $anak->id]);

        $this->assertDatabaseMissing('finance_categories', ['name' => 'Baru']);
    }

    public function test_jenis_induk_harus_sama(): void
    {
        $induk = $this->kat('keluar');

        $this->kirimKategori(['parent_id' => $induk->id, 'type' => 'masuk']);

        $this->assertDatabaseMissing('finance_categories', ['name' => 'Baru']);
    }

    public function test_sub_kategori_valid_tersimpan(): void
    {
        $induk = $this->kat('masuk');

        $this->kirimKategori(['parent_id' => $induk->id]);

        $this->assertDatabaseHas('finance_categories', ['name' => 'Baru', 'parent_id' => $induk->id]);
    }

    public function test_induk_diri_sendiri_ditolak_bukan_diam_diam_dikosongkan(): void
    {
        $k = $this->kat('masuk');

        $this->actingAs($this->superAdmin())->put("/patra/finance-category/{$k->id}", [
            'name' => 'Ganti', 'type' => 'masuk', 'parent_id' => $k->id,
        ]);

        $this->assertDatabaseHas('finance_categories', ['id' => $k->id, 'name' => $k->name]);
    }

    public function test_kategori_yang_punya_anak_tidak_boleh_jadi_anak(): void
    {
        $a = $this->kat('masuk');
        $this->kat('masuk', ['parent_id' => $a->id]);
        $b = $this->kat('masuk');

        $this->actingAs($this->superAdmin())->put("/patra/finance-category/{$a->id}", [
            'name' => 'A2', 'type' => 'masuk', 'parent_id' => $b->id,
        ]);

        $this->assertDatabaseHas('finance_categories', ['id' => $a->id, 'parent_id' => null]);
    }

    public function test_jenis_tidak_boleh_berubah_bila_sudah_dipakai_transaksi(): void
    {
        $k = $this->kat('masuk');
        $this->trx($k);

        $this->actingAs($this->superAdmin())->put("/patra/finance-category/{$k->id}", [
            'name' => 'X', 'type' => 'keluar',
        ]);

        $this->assertDatabaseHas('finance_categories', ['id' => $k->id, 'type' => 'masuk']);
    }

    public function test_jenis_tidak_boleh_berubah_bila_punya_sub_kategori(): void
    {
        $k = $this->kat('masuk');
        $this->kat('masuk', ['parent_id' => $k->id]);

        $this->actingAs($this->superAdmin())->put("/patra/finance-category/{$k->id}", [
            'name' => 'X', 'type' => 'keluar',
        ]);

        $this->assertDatabaseHas('finance_categories', ['id' => $k->id, 'type' => 'masuk']);
    }

    public function test_ubah_nama_kategori_yang_dipakai_tetap_boleh(): void
    {
        $k = $this->kat('masuk');
        $this->trx($k);

        $this->actingAs($this->superAdmin())->put("/patra/finance-category/{$k->id}", [
            'name' => 'Nama Baru', 'type' => 'masuk',
        ]);

        $this->assertDatabaseHas('finance_categories', ['id' => $k->id, 'name' => 'Nama Baru']);
    }
}

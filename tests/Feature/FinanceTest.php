<?php

namespace Tests\Feature;

use App\Models\FinanceCategory;
use App\Models\FinanceTransaction;
use Tests\TestCase;

class FinanceTest extends TestCase
{
    private function kategori(string $type): FinanceCategory
    {
        return FinanceCategory::where('type', $type)->first()
            ?? FinanceCategory::create(['name' => "Uji {$type}", 'type' => $type, 'is_active' => true]);
    }

    private function trx(string $type, int $amount, string $date): FinanceTransaction
    {
        return FinanceTransaction::create([
            'transaction_number' => FinanceTransaction::generateTransactionNumber(),
            'date' => $date,
            'description' => "Uji {$type}",
            'finance_category_id' => $this->kategori($type)->id,
            'type' => $type,
            'amount' => $amount,
            'payment_method' => 'Transfer',
        ]);
    }

    public function test_nomor_transaksi_berurutan(): void
    {
        $a = $this->trx('masuk', 1000, '2026-10-01');
        $b = $this->trx('keluar', 500, '2026-10-02');

        $this->assertSame('ED-0001', $a->transaction_number);
        $this->assertSame('ED-0002', $b->transaction_number);
    }

    public function test_simpan_transaksi_via_form(): void
    {
        $sa = $this->superAdmin();
        $kat = $this->kategori('masuk');

        $this->actingAs($sa)->post('/patra/finance', [
            'date' => '2026-10-05', 'description' => 'DP proyek', 'finance_category_id' => $kat->id,
            'type' => 'masuk', 'amount' => 2500000, 'payment_method' => 'Transfer',
        ])->assertRedirect();

        $this->assertDatabaseHas('finance_transactions', ['description' => 'DP proyek']);
    }

    public function test_jumlah_nol_atau_negatif_ditolak(): void
    {
        $sa = $this->superAdmin();
        $kat = $this->kategori('masuk');

        foreach ([0, -5, 'abc'] as $amt) {
            $this->actingAs($sa)->post('/patra/finance', [
                'date' => '2026-10-05', 'description' => 'x', 'finance_category_id' => $kat->id,
                'type' => 'masuk', 'amount' => $amt, 'payment_method' => 'Tunai',
            ]);
        }
        $this->assertSame(0, FinanceTransaction::count());
    }

    public function test_saldo_berjalan_tampil_benar_di_index(): void
    {
        $this->trx('masuk', 1000000, '2026-10-01');
        $this->trx('keluar', 250000, '2026-10-02');
        $this->trx('masuk', 50000, '2026-10-03');

        $res = $this->actingAs($this->superAdmin())->get('/patra/finance');

        $res->assertOk();
        // 1.000.000 - 250.000 + 50.000 = 800.000
        $res->assertSee('800.000');
    }

    public function test_kategori_terpakai_tidak_bisa_dihapus(): void
    {
        $t = $this->trx('masuk', 1000, '2026-10-01');

        $this->actingAs($this->superAdmin())->delete("/patra/finance-category/{$t->finance_category_id}");

        $this->assertNotNull(FinanceCategory::find($t->finance_category_id));
    }

    public function test_hapus_transaksi_bisa_dipulihkan_dan_ada_jejak(): void
    {
        $this->pendingFix('Fase 4 (soft delete + audit log keuangan)');

        $t = $this->trx('masuk', 1000, '2026-10-01');
        $this->actingAs($this->superAdmin())->delete("/patra/finance/{$t->id}");

        $this->assertSoftDeleted('finance_transactions', ['id' => $t->id]);
    }

    public function test_transaksi_pada_periode_tertutup_tidak_bisa_diubah(): void
    {
        $this->pendingFix('Fase 4 (tutup periode)');

        $this->fail('Tulis setelah fitur tutup periode ada.');
    }
}

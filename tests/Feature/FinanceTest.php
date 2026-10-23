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

    public function test_hapus_transaksi_adalah_soft_delete_dan_saldo_menyesuaikan(): void
    {
        $sa = $this->superAdmin();
        $a = $this->trx('masuk', 1000, '2026-10-01');
        $b = $this->trx('keluar', 400, '2026-10-02');

        $this->actingAs($sa)->delete("/patra/finance/{$b->id}")->assertRedirect();

        $this->assertSoftDeleted('finance_transactions', ['id' => $b->id]);
        $this->assertSame($sa->id, (int) FinanceTransaction::withTrashed()->find($b->id)->deleted_by);
        $this->assertEquals(1000, $this->actingAs($sa)->get('/patra/finance')->viewData('saldoSaatIni'));
    }

    public function test_transaksi_terhapus_bisa_dipulihkan(): void
    {
        $sa = $this->superAdmin();
        $t = $this->trx('masuk', 1000, '2026-10-01');
        $this->actingAs($sa)->delete("/patra/finance/{$t->id}");

        $this->actingAs($sa)->get('/patra/finance/trash')->assertOk()->assertSee($t->transaction_number);
        $this->actingAs($sa)->post("/patra/finance/{$t->id}/restore")->assertRedirect();

        $this->assertNotSoftDeleted('finance_transactions', ['id' => $t->id]);
        $this->assertNull(FinanceTransaction::find($t->id)->deleted_by);
    }

    public function test_nomor_transaksi_terhapus_tidak_dipakai_ulang(): void
    {
        $a = $this->trx('masuk', 1000, '2026-10-01');
        $a->delete();

        $this->assertSame('ED-0002', FinanceTransaction::generateTransactionNumber());
    }

    public function test_aksi_penghapusan_tercatat_di_log_aktivitas(): void
    {
        $sa = $this->superAdmin();
        $t = $this->trx('masuk', 1000, '2026-10-01');
        $before = \DB::table('logs')->count();

        $this->actingAs($sa)->delete("/patra/finance/{$t->id}");

        $this->assertGreaterThan($before, \DB::table('logs')->count());
    }

    // ---- Penutupan periode ----

    private function tutup(string $period)
    {
        return $this->post('/patra/finance/period/close', ['period' => $period]);
    }

    public function test_tutup_periode_mencatat_pelaku(): void
    {
        $sa = $this->superAdmin();
        $this->actingAs($sa);
        $this->tutup('2026-09')->assertRedirect();

        $this->assertDatabaseHas('finance_periods', ['period' => '2026-09', 'is_closed' => 1, 'closed_by' => $sa->id]);
    }

    public function test_periode_tertutup_memblokir_tambah_ubah_hapus(): void
    {
        $sa = $this->superAdmin();
        $t = $this->trx('masuk', 1000, '2026-09-15');
        $kat = $t->finance_category_id;
        $this->actingAs($sa);
        $this->tutup('2026-09');

        // tambah
        $this->post('/patra/finance', ['date' => '2026-09-20', 'description' => 'baru', 'finance_category_id' => $kat,
            'type' => 'masuk', 'amount' => 5, 'payment_method' => 'Tunai']);
        $this->assertSame(1, FinanceTransaction::count());

        // ubah
        $this->put("/patra/finance/{$t->id}", ['date' => '2026-09-15', 'description' => 'diubah', 'finance_category_id' => $kat,
            'type' => 'masuk', 'amount' => 9999, 'payment_method' => 'Tunai']);
        $this->assertEquals(1000, $t->fresh()->amount);

        // pindahkan ke periode tertutup dari periode terbuka
        $o = $this->trx('masuk', 50, '2026-10-02');
        $this->put("/patra/finance/{$o->id}", ['date' => '2026-09-10', 'description' => 'x', 'finance_category_id' => $kat,
            'type' => 'masuk', 'amount' => 50, 'payment_method' => 'Tunai']);
        $this->assertSame('2026-10-02', $o->fresh()->date->format('Y-m-d'));

        // hapus
        $this->delete("/patra/finance/{$t->id}");
        $this->assertNotSoftDeleted('finance_transactions', ['id' => $t->id]);
    }

    public function test_ubah_transaksi_di_periode_terbuka_berhasil(): void
    {
        // Kontrol untuk test pemblokiran: perubahan yang sama harus BERHASIL bila periode terbuka.
        $t = $this->trx('masuk', 1000, '2026-09-15');

        $this->actingAs($this->superAdmin())->put("/patra/finance/{$t->id}", [
            'date' => '2026-09-15', 'description' => 'diubah', 'finance_category_id' => $t->finance_category_id,
            'type' => 'masuk', 'amount' => 9999, 'payment_method' => 'Tunai',
        ]);

        $this->assertEquals(9999, $t->fresh()->amount);
    }

    public function test_periode_terbuka_tidak_terpengaruh_periode_lain_yang_tertutup(): void
    {
        $sa = $this->superAdmin();
        $t = $this->trx('masuk', 1000, '2026-10-05');
        $this->actingAs($sa);
        $this->tutup('2026-09');

        $this->delete("/patra/finance/{$t->id}");

        $this->assertSoftDeleted('finance_transactions', ['id' => $t->id]);
    }

    public function test_restore_diblokir_jika_periode_tertutup(): void
    {
        $sa = $this->superAdmin();
        $t = $this->trx('masuk', 1000, '2026-09-15');
        $t->delete();
        $this->actingAs($sa);
        $this->tutup('2026-09');

        $this->post("/patra/finance/{$t->id}/restore");

        $this->assertSoftDeleted('finance_transactions', ['id' => $t->id]);
    }

    public function test_buka_kembali_wajib_alasan_dan_mengembalikan_akses(): void
    {
        $sa = $this->superAdmin();
        $t = $this->trx('masuk', 1000, '2026-09-15');
        $this->actingAs($sa);
        $this->tutup('2026-09');

        $this->post('/patra/finance/period/reopen', ['period' => '2026-09', 'reason' => 'pendek']);
        $this->assertDatabaseHas('finance_periods', ['period' => '2026-09', 'is_closed' => 1]);

        $this->post('/patra/finance/period/reopen', ['period' => '2026-09', 'reason' => 'Koreksi nominal faktur vendor']);
        $this->assertDatabaseHas('finance_periods', [
            'period' => '2026-09', 'is_closed' => 0, 'reopened_by' => $sa->id, 'reopen_reason' => 'Koreksi nominal faktur vendor',
        ]);

        $this->delete("/patra/finance/{$t->id}");
        $this->assertSoftDeleted('finance_transactions', ['id' => $t->id]);
    }

    public function test_periode_masa_depan_dan_format_salah_ditolak(): void
    {
        $this->actingAs($this->superAdmin());

        $this->tutup(now()->addMonths(2)->format('Y-m'));
        $this->tutup('2026-13');
        $this->tutup('abc');

        $this->assertSame(0, \App\Models\FinancePeriod::count());
    }

    public function test_halaman_periode_dan_sampah_tampil(): void
    {
        $sa = $this->superAdmin();
        $this->actingAs($sa);
        $this->tutup('2026-09');

        $this->get('/patra/finance/period')->assertOk()->assertSee('2026-09');
        $this->get('/patra/finance/trash')->assertOk();
        $this->get('/patra/finance')->assertOk();
    }

    public function test_admin_dan_moderator_tidak_bisa_tutup_atau_buka_periode(): void
    {
        $this->actingAs($this->admin())->post('/patra/finance/period/close', ['period' => '2026-09'])->assertForbidden();
        $this->actingAs($this->moderator())->post('/patra/finance/period/reopen', ['period' => '2026-09', 'reason' => 'abcdefghijk'])->assertForbidden();
    }

    // ---- Saldo: hasil query database harus sama dengan hitungan manual ----

    public function test_saldo_berjalan_sama_dengan_hitungan_manual_pada_data_acak(): void
    {
        mt_srand(42);
        $kat = [$this->kategori('masuk')->id, $this->kategori('keluar')->id];
        for ($i = 0; $i < 75; $i++) {
            $type = mt_rand(0, 2) ? 'masuk' : 'keluar';
            $this->trx($type, mt_rand(1, 500) * 1000, sprintf('2026-%02d-%02d', mt_rand(1, 10), mt_rand(1, 28)));
        }
        // beberapa terhapus -> tidak boleh ikut saldo
        FinanceTransaction::orderBy('id')->limit(5)->get()->each->delete();

        // Rujukan: algoritma lama (muat semua, hitung di PHP).
        $saldo = 0;
        $expected = [];
        foreach (FinanceTransaction::orderBy('date')->orderBy('id')->get() as $r) {
            $saldo += $r->type === 'masuk' ? (float) $r->amount : -(float) $r->amount;
            $expected[$r->id] = $saldo;
        }

        $sa = $this->superAdmin();
        $seen = 0;
        foreach ([1, 2, 3, 4] as $page) {
            $v = $this->actingAs($sa)->get("/patra/finance?page={$page}&sort=asc")->viewData('table');
            foreach ($v as $row) {
                $this->assertEquals($expected[$row->id], (float) $row->saldo_berjalan, "saldo baris {$row->transaction_number}");
                $seen++;
            }
        }
        $this->assertSame(70, $seen);
        $this->assertEquals($saldo, $this->actingAs($sa)->get('/patra/finance')->viewData('saldoSaatIni'));
    }

    public function test_saldo_tetap_saldo_total_walau_daftar_difilter(): void
    {
        $this->trx('masuk', 1000, '2026-08-01');
        $this->trx('masuk', 500, '2026-09-01');
        $this->trx('keluar', 200, '2026-09-02');

        $v = $this->actingAs($this->superAdmin())->get('/patra/finance?bulan=2026-09&sort=asc');
        $rows = $v->viewData('table');

        $this->assertCount(2, $rows);
        $this->assertEquals(1500, (float) $rows[0]->saldo_berjalan); // termasuk Agustus
        $this->assertEquals(1300, (float) $rows[1]->saldo_berjalan);
        $this->assertEquals(500, (float) $v->viewData('totalMasuk'));
        $this->assertEquals(200, (float) $v->viewData('totalKeluar'));
    }

    public function test_filter_bulan_mencakup_hari_terakhir_bulan(): void
    {
        $this->trx('masuk', 100, '2026-09-30');
        $this->trx('masuk', 100, '2026-10-01');

        $rows = $this->actingAs($this->superAdmin())->get('/patra/finance?bulan=2026-09')->viewData('table');

        $this->assertCount(1, $rows);
    }

    public function test_index_tidak_memuat_semua_baris(): void
    {
        for ($i = 0; $i < 45; $i++) {
            $this->trx('masuk', 10, '2026-10-01');
        }

        $rows = $this->actingAs($this->superAdmin())->get('/patra/finance')->viewData('table');

        $this->assertSame(45, $rows->total());
        $this->assertCount(20, $rows->items());
    }
}

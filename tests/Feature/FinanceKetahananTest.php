<?php

namespace Tests\Feature;

use App\Models\FinanceCategory;
use App\Models\FinancePeriod;
use App\Models\FinanceTransaction;
use App\Services\FinanceImportService;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

/** A8-A11 (impor), B3, B5, B6 (ketahanan data keuangan). */
class FinanceKetahananTest extends TestCase
{
    private array $tmp = [];
    private $sa;

    protected function tearDown(): void
    {
        foreach ($this->tmp as $f) {
            @unlink($f);
        }
        parent::tearDown();
    }

    private function sa()
    {
        return $this->sa ??= $this->superAdmin();
    }

    private function kat(string $type = 'masuk', bool $aktif = true, string $nama = null): FinanceCategory
    {
        return FinanceCategory::create(['name' => $nama ?? 'K' . uniqid(), 'type' => $type, 'is_active' => $aktif]);
    }

    /** Baris: [tanggal(teks), no, deskripsi, masuk, keluar, catatan] semua sebagai teks. */
    private function xlsx(array $rows): string
    {
        $ss = new Spreadsheet();
        $ws = $ss->getActiveSheet();
        $ws->fromArray(['Tanggal', 'No. Transaksi', 'Deskripsi', 'Kategori', 'Sub Kategori', 'Uang Masuk (Rp.)', 'Uang Keluar (Rp.)', 'Metode Bayar', 'Saldo Akhir', 'Catatan'], null, 'A1');
        foreach ($rows as $i => $r) {
            $line = $i + 2;
            foreach ([$r[0], $r[1], $r[2], 'Pendapatan', '', $r[3], $r[4], 'Transfer', '', $r[5] ?? ''] as $c => $v) {
                $ws->setCellValueExplicit([$c + 1, $line], (string) $v, DataType::TYPE_STRING);
            }
        }
        $path = tempnam(sys_get_temp_dir(), 'kt') . '.xlsx';
        (new Xlsx($ss))->save($path);

        return $this->tmp[] = $path;
    }

    private function parse(array $rows): array
    {
        $svc = app(FinanceImportService::class);

        return $svc->annotate($svc->parse($this->xlsx($rows), 'asis')['rows']);
    }

    // ---------- A8
    public function test_nomor_dan_catatan_terlalu_panjang_ditandai_error_per_baris(): void
    {
        $rows = $this->parse([
            ['10/10/2025', str_repeat('N', 80), 'ok', '1000', '', ''],
            ['11/10/2025', 'ED-1', 'ok', '1000', '', str_repeat('c', 2500)],
            ['12/10/2025', 'ED-2', 'ok', '1000', '', 'catatan wajar'],
        ]);

        $this->assertSame('error', $rows[0]['status']);
        $this->assertStringContainsString('No. Transaksi', $rows[0]['reason']);
        $this->assertSame('error', $rows[1]['status']);
        $this->assertStringContainsString('Catatan', $rows[1]['reason']);
        $this->assertSame('ok', $rows[2]['status']);
    }

    // ---------- A9
    public function test_angka_tanda_kurung_dan_notasi_ilmiah_ditolak(): void
    {
        $rows = $this->parse([
            ['10/10/2025', 'A1', 'x', '(1.000)', '', ''],
            ['10/10/2025', 'A2', 'x', '1e3', '', ''],
            ['10/10/2025', 'A3', 'x', '1.000', '', ''],
        ]);

        $this->assertSame('error', $rows[0]['status']);
        $this->assertStringContainsString('Format angka', $rows[0]['reason']);
        $this->assertSame('error', $rows[1]['status']);
        $this->assertSame('ok', $rows[2]['status']);
        $this->assertSame(1000.0, $rows[2]['amount']);
    }

    public function test_tahun_di_luar_rentang_ditolak(): void
    {
        $rows = $this->parse([
            ['10/10/1905', 'B1', 'x', '1000', '', ''],
            ['10/10/2999', 'B2', 'x', '1000', '', ''],
            ['10/10/2025', 'B3', 'x', '1000', '', ''],
        ]);

        $this->assertSame('error', $rows[0]['status']);
        $this->assertStringContainsString('Tahun', $rows[0]['reason']);
        $this->assertSame('error', $rows[1]['status']);
        $this->assertSame('ok', $rows[2]['status']);
    }

    // ---------- A10
    public function test_duplikat_tanpa_nomor_juga_memeriksa_tempat_sampah(): void
    {
        $k = $this->kat();
        $t = FinanceTransaction::createWithAutoNumber([
            'date' => '2025-10-10', 'description' => 'Sewa', 'finance_category_id' => $k->id,
            'type' => 'masuk', 'amount' => 1000, 'payment_method' => 'Transfer',
        ]);
        $t->delete();

        $rows = $this->parse([['10/10/2025', '', 'Sewa', '1000', '', '']]);

        $this->assertSame('skip', $rows[0]['status']);
        $this->assertStringContainsString('Sampah', $rows[0]['reason']);
    }

    public function test_duplikat_dalam_satu_file_tanpa_nomor_diberi_peringatan_tetap_boleh(): void
    {
        $rows = $this->parse([
            ['10/10/2025', '', 'Parkir', '1000', '', ''],
            ['10/10/2025', '', 'Parkir', '1000', '', ''],
        ]);

        $this->assertSame('ok', $rows[0]['status']);
        $this->assertSame('ok', $rows[1]['status']);
        $this->assertEmpty($rows[0]['warning'] ?? null);
        $this->assertStringContainsString('Sama', $rows[1]['warning']);
    }

    // ---------- A11
    private function simpan(array $rows, array $override = [])
    {
        $annotated = app(FinanceImportService::class)->annotate($rows);

        return $this->actingAs($this->sa())
            ->withSession(['finance_import' => ['token' => 't', 'rows' => $annotated, 'user' => $this->sa()->id]])
            ->post(route('patra.finance.import.store'), ['token' => 't', 'category' => $override]);
    }

    private function baris(int $n, ?int $kategori): array
    {
        return [
            'excel_row' => $n + 1, 'date' => '2025-06-10', 'number' => '', 'description' => "T{$n}", 'type' => 'masuk',
            'amount' => 1000.0, 'payment_method' => 'Transfer', 'notes' => null, 'hint' => '', 'balance' => null,
            'category_id' => $kategori, 'error' => null,
        ];
    }

    public function test_semua_baris_tanpa_kategori_dilaporkan_sekaligus_dan_tidak_ada_yang_tersimpan(): void
    {
        $k = $this->kat();
        $salah = $this->kat('keluar');

        // Baris 2 dan 3 diarahkan ke kategori berjenis keluar padahal transaksinya masuk.
        $this->simpan([$this->baris(1, $k->id), $this->baris(2, $k->id), $this->baris(3, $k->id)], [1 => $salah->id, 2 => $salah->id]);

        $this->assertSame(0, FinanceTransaction::count());
        $msg = (string) session('alert.config');
        $this->assertStringContainsString('3', $msg);
        $this->assertStringContainsString('4', $msg);
    }

    public function test_token_salah_ditolak(): void
    {
        $this->actingAs($this->sa())
            ->withSession(['finance_import' => ['token' => 'benar', 'rows' => [], 'user' => $this->sa()->id]])
            ->post(route('patra.finance.import.store'), ['token' => 'salah'])
            ->assertRedirect(route('patra.finance.import.create'));
    }

    // ---------- B3
    public function test_hapus_transaksi_dibatalkan_seluruhnya_bila_gagal_di_tengah(): void
    {
        $k = $this->kat();
        $t = FinanceTransaction::createWithAutoNumber([
            'date' => '2025-10-10', 'description' => 'X', 'finance_category_id' => $k->id,
            'type' => 'masuk', 'amount' => 1000, 'payment_method' => 'Transfer',
        ]);
        FinanceTransaction::deleting(fn () => throw new \RuntimeException('gagal'));

        $this->actingAs($this->sa())->delete("/patra/finance/{$t->id}");

        $fresh = FinanceTransaction::withTrashed()->find($t->id);
        $this->assertNull($fresh->deleted_at);
        $this->assertNull($fresh->deleted_by, 'deleted_by tidak boleh tertinggal bila hapus gagal');
    }

    public function test_pulihkan_transaksi_dibatalkan_seluruhnya_bila_gagal_di_tengah(): void
    {
        $k = $this->kat();
        $t = FinanceTransaction::createWithAutoNumber([
            'date' => '2025-10-10', 'description' => 'X', 'finance_category_id' => $k->id,
            'type' => 'masuk', 'amount' => 1000, 'payment_method' => 'Transfer',
        ]);
        $t->forceFill(['deleted_by' => $this->sa()->id])->saveQuietly();
        $t->delete();
        FinanceTransaction::restored(fn () => throw new \RuntimeException('gagal'));

        $this->actingAs($this->sa())->post("/patra/finance/{$t->id}/restore");

        $this->assertNotNull(FinanceTransaction::withTrashed()->find($t->id)->deleted_at);
    }

    // ---------- B5
    public function test_pencarian_kategori_meng_escape_wildcard(): void
    {
        $this->kat('masuk', true, 'Diskon 50%');
        $this->kat('masuk', true, 'Diskon 500');
        $this->kat('masuk', true, 'a_b');
        $this->kat('masuk', true, 'axb');

        $res = $this->actingAs($this->sa())->get('/patra/finance-category?search=' . urlencode('50%'));
        $res->assertSee('Diskon 50%')->assertDontSee('Diskon 500');

        $res = $this->actingAs($this->sa())->get('/patra/finance-category?search=' . urlencode('a_b'));
        $res->assertSee('a_b')->assertDontSee('axb');
    }

    // ---------- B6
    private function payload(FinanceCategory $k, array $o = []): array
    {
        return array_merge([
            'date' => '2025-10-05', 'description' => 'Uji', 'finance_category_id' => $k->id,
            'type' => $k->type, 'amount' => 1000, 'payment_method' => 'Transfer',
        ], $o);
    }

    public function test_batas_jumlah_catatan_dan_tanggal_pada_form_transaksi(): void
    {
        $k = $this->kat();
        $sa = $this->sa();

        foreach ([
            ['amount' => '99999999999999'],
            ['notes' => str_repeat('c', 2001)],
            ['date' => '1899-01-01'],
            ['date' => '2150-01-01'],
        ] as $buruk) {
            $this->actingAs($sa)->post('/patra/finance', $this->payload($k, $buruk));
        }
        $this->assertSame(0, FinanceTransaction::count());

        $this->actingAs($sa)->post('/patra/finance', $this->payload($k, ['amount' => 9999999999999, 'notes' => str_repeat('c', 2000)]));
        $this->assertSame(1, FinanceTransaction::count());
    }

    public function test_tutup_periode_bersamaan_memberi_pesan_jelas_bukan_error_database(): void
    {
        FinancePeriod::creating(function ($p) {
            // Pengguna lain menutup periode yang sama tepat sebelum insert ini.
            \Illuminate\Support\Facades\DB::table('finance_periods')->insert([
                'period' => $p->period, 'is_closed' => true, 'created_at' => now(), 'updated_at' => now(),
            ]);
        });

        $this->actingAs($this->sa())->post('/patra/finance/period/close', ['period' => now()->subMonth()->format('Y-m')]);

        $msg = (string) session('alert.config');
        $this->assertStringContainsString('sudah ditutup', $msg);
        $this->assertStringNotContainsString('UNIQUE', $msg);
    }
}

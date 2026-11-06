<?php

namespace Tests\Feature;

use App\Models\FinanceCategory;
use App\Models\FinancePeriod;
use App\Models\FinanceTransaction;
use App\Services\FinanceImportService;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/** A4 (kinerja impor, tanpa query per baris) dan A5 (nomor transaksi aman dari benturan). */
class FinanceImportKinerjaTest extends TestCase
{
    private FinanceCategory $kategoriMasuk;

    protected function setUp(): void
    {
        parent::setUp();
        $this->kategoriMasuk = FinanceCategory::create(['name' => 'Pendapatan', 'type' => 'masuk', 'is_active' => true]);
    }

    private function baris(int $n, array $override = []): array
    {
        return array_merge([
            'excel_row' => $n + 1,
            'date' => '2025-06-' . str_pad((string) (($n % 28) + 1), 2, '0', STR_PAD_LEFT),
            'number' => '',
            'description' => 'Transaksi ' . $n,
            'type' => 'masuk',
            'amount' => 1000.0 + $n,
            'payment_method' => 'Transfer',
            'notes' => null,
            'hint' => '',
            'balance' => null,
            'category_id' => $this->kategoriMasuk->id,
            'error' => null,
        ], $override);
    }

    private function hitungSelect(callable $aksi): int
    {
        $selects = 0;
        DB::listen(function ($q) use (&$selects) {
            if (stripos(ltrim($q->sql), 'select') === 0) {
                $selects++;
            }
        });
        $aksi();

        return $selects;
    }

    private function simpanLewatController(array $rows): void
    {
        $annotated = app(FinanceImportService::class)->annotate($rows);

        $this->actingAs($this->superAdmin())
            ->withSession(['finance_import' => ['token' => 't', 'rows' => $annotated, 'user' => auth()->id()]])
            ->post(route('patra.finance.import.store'), ['token' => 't'])
            ->assertRedirect(route('patra.finance.index'));
    }

    // ---------- A4
    public function test_anotasi_300_baris_tidak_menjalankan_query_per_baris(): void
    {
        $rows = array_map(fn ($i) => $this->baris($i), range(0, 299));

        $selects = $this->hitungSelect(fn () => app(FinanceImportService::class)->annotate($rows));

        $this->assertLessThan(15, $selects, "annotate() menjalankan $selects query SELECT untuk 300 baris");
    }

    public function test_simpan_300_baris_tidak_menjalankan_query_select_per_baris(): void
    {
        $rows = array_map(fn ($i) => $this->baris($i), range(0, 299));
        $selects = 0;

        $selects = $this->hitungSelect(fn () => $this->simpanLewatController($rows));

        $this->assertSame(300, FinanceTransaction::count());
        $this->assertLessThan(40, $selects, "store() menjalankan $selects query SELECT untuk 300 baris");
    }

    public function test_deteksi_duplikat_tanpa_nomor_tetap_bekerja(): void
    {
        FinanceTransaction::create([
            'transaction_number' => 'ED-0001', 'date' => '2025-06-01', 'description' => 'Sudah Ada',
            'finance_category_id' => $this->kategoriMasuk->id, 'type' => 'masuk', 'amount' => 5000,
            'payment_method' => 'Transfer',
        ]);

        $rows = app(FinanceImportService::class)->annotate([
            $this->baris(0, ['date' => '2025-06-01', 'description' => 'sudah ada ', 'amount' => 5000.0]),   // sama (huruf besar/kecil & spasi beda)
            $this->baris(1, ['date' => '2025-06-01', 'description' => 'Sudah Ada', 'amount' => 5001.0]),    // nominal beda
            $this->baris(2, ['date' => '2025-06-02', 'description' => 'Sudah Ada', 'amount' => 5000.0]),    // tanggal beda
        ]);

        $this->assertSame('skip', $rows[0]['status']);
        $this->assertSame('ok', $rows[1]['status']);
        $this->assertSame('ok', $rows[2]['status']);
    }

    public function test_nomor_duplikat_dan_periode_tertutup_tetap_dilewati_saat_simpan(): void
    {
        FinancePeriod::create(['period' => '2025-07', 'is_closed' => true]);
        FinanceTransaction::create([
            'transaction_number' => 'ED-0007', 'date' => '2025-06-01', 'description' => 'Lama',
            'finance_category_id' => $this->kategoriMasuk->id, 'type' => 'masuk', 'amount' => 1,
            'payment_method' => 'Transfer',
        ]);

        $this->simpanLewatController([
            $this->baris(0, ['number' => 'ED-0007']),                       // nomor sudah ada
            $this->baris(1, ['number' => 'ED-0100']),                       // baru
            $this->baris(2, ['date' => '2025-07-10']),                      // periode tertutup
            $this->baris(3, ['number' => 'ed-0100', 'date' => '2025-06-05']), // nomor sama dalam satu file (huruf kecil)
        ]);

        $this->assertSame(['ED-0007', 'ED-0100'], FinanceTransaction::orderBy('transaction_number')->pluck('transaction_number')->all());
    }

    public function test_baris_tanpa_nomor_diberi_nomor_berurutan_setelah_nomor_terbesar(): void
    {
        FinanceTransaction::create([
            'transaction_number' => 'ED-0050', 'date' => '2025-06-01', 'description' => 'Lama',
            'finance_category_id' => $this->kategoriMasuk->id, 'type' => 'masuk', 'amount' => 1,
            'payment_method' => 'Transfer',
        ]);

        $this->simpanLewatController([
            $this->baris(0), $this->baris(1, ['number' => 'ED-0200']), $this->baris(2), $this->baris(3),
        ]);

        $this->assertSame(
            ['ED-0050', 'ED-0051', 'ED-0052', 'ED-0053', 'ED-0200'],
            FinanceTransaction::orderBy('transaction_number')->pluck('transaction_number')->all()
        );
    }

    // ---------- A5
    public function test_nomor_otomatis_mengulang_bila_keburu_dipakai_request_lain(): void
    {
        foreach (['ED-0004', 'ED-0005'] as $no) {
            FinanceTransaction::create([
                'transaction_number' => $no, 'date' => '2025-06-01', 'description' => $no,
                'finance_category_id' => $this->kategoriMasuk->id, 'type' => 'masuk', 'amount' => 1,
                'payment_method' => 'Transfer',
            ]);
        }

        // Kursor basi (meniru request lain yang menyimpan duluan): mencoba ED-0004, bentrok, lalu membaca ulang.
        $cursor = 3;
        $trx = FinanceTransaction::createWithAutoNumber([
            'date' => '2025-06-02', 'description' => 'Baru', 'finance_category_id' => $this->kategoriMasuk->id,
            'type' => 'masuk', 'amount' => 10, 'payment_method' => 'Transfer',
        ], $cursor);

        $this->assertSame('ED-0006', $trx->transaction_number);
        $this->assertSame(6, $cursor);
    }

    public function test_nomor_terhapus_tidak_dipakai_ulang(): void
    {
        $a = FinanceTransaction::createWithAutoNumber([
            'date' => '2025-06-02', 'description' => 'A', 'finance_category_id' => $this->kategoriMasuk->id,
            'type' => 'masuk', 'amount' => 10, 'payment_method' => 'Transfer',
        ]);
        $a->delete();

        $b = FinanceTransaction::createWithAutoNumber([
            'date' => '2025-06-02', 'description' => 'B', 'finance_category_id' => $this->kategoriMasuk->id,
            'type' => 'masuk', 'amount' => 10, 'payment_method' => 'Transfer',
        ]);

        $this->assertSame('ED-0001', $a->transaction_number);
        $this->assertSame('ED-0002', $b->transaction_number);
    }

    public function test_error_database_selain_nomor_ganda_tidak_diulang_diam_diam(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        // description NOT NULL: bukan benturan nomor, jadi harus dilempar, bukan diulang.
        FinanceTransaction::createWithAutoNumber([
            'date' => '2025-06-02', 'description' => null, 'finance_category_id' => $this->kategoriMasuk->id,
            'type' => 'masuk', 'amount' => 10, 'payment_method' => 'Transfer',
        ]);
    }

    public function test_form_tambah_transaksi_memakai_nomor_otomatis_berurutan(): void
    {
        $admin = $this->superAdmin();
        $data = ['date' => '2025-06-02', 'description' => 'Via form', 'finance_category_id' => $this->kategoriMasuk->id,
            'type' => 'masuk', 'amount' => 25000, 'payment_method' => 'Transfer'];

        $this->actingAs($admin)->post(route('patra.finance.store'), $data);
        $this->post(route('patra.finance.store'), $data);

        $this->assertSame(['ED-0001', 'ED-0002'], FinanceTransaction::orderBy('transaction_number')->pluck('transaction_number')->all());
    }
}

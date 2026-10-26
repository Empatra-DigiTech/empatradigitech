<?php

namespace Tests\Feature;

use App\Models\FinanceCategory;
use App\Models\FinancePeriod;
use App\Models\FinanceTransaction;
use App\Services\FinanceImportService;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class FinanceImportTest extends TestCase
{
    private array $tmp = [];

    protected function tearDown(): void
    {
        foreach ($this->tmp as $f) {
            @unlink($f);
        }
        parent::tearDown();
    }

    private function kategori(): void
    {
        $jasa = FinanceCategory::create(['name' => 'Pendapatan Jasa / Proyek', 'type' => 'masuk', 'is_active' => true]);
        FinanceCategory::create(['name' => 'Website', 'type' => 'masuk', 'parent_id' => $jasa->id, 'is_active' => true]);
        FinanceCategory::create(['name' => 'Joki Tugas', 'type' => 'masuk', 'parent_id' => $jasa->id, 'is_active' => true]);
        FinanceCategory::create(['name' => 'Lainnya', 'type' => 'masuk', 'parent_id' => $jasa->id, 'is_active' => true]);
        $gaji = FinanceCategory::create(['name' => 'Gaji & Bonus', 'type' => 'keluar', 'is_active' => true]);
        FinanceCategory::create(['name' => 'Gaji Developer', 'type' => 'keluar', 'parent_id' => $gaji->id, 'is_active' => true]);
        FinanceCategory::create(['name' => 'Hosting & Domain', 'type' => 'keluar', 'is_active' => true]);
        FinanceCategory::create(['name' => 'Lainnya', 'type' => 'keluar', 'is_active' => true]);
    }

    /**
     * Meniru quirk file asli: sel tanggal Excel yang hari/bulannya tertukar (5 Feb = maksud 2 Mei),
     * tanggal teks dd/mm/yyyy untuk hari > 12, dan sheet kedua yang harus diabaikan.
     */
    private function xlsx(array $rows = null): string
    {
        $rows ??= [
            // tanggal, no, deskripsi, kategori, sub, masuk, keluar, metode, saldo
            [['cell', 2025, 2, 5], 'ED-0001', 'Kas Masuk Joki Tugas', 'Pendapatan', 'Mas Dwi', 250000, 0, 'Transfer', 250000],
            [['text', '27/5/2025'], 'ED-0002', 'Kas Masuk DP LMS', 'Pendapatan', 'Kaigo.Lab', '5.000.000', 0, 'Transfer', 5250000],
            [['text', '28/6/2025'], 'ED-0003', 'Kas Keluar Hosting', 'Keluar', 'Hostinger 48 Bulan', 0, 1700697, 'QRIS', 3549303],
            [['cell', 2025, 11, 7], 'ED-0004', 'Kas Keluar', 'Keluar', 'Gaji Dev', 0, 549303, 'Transfer', 3000000],
            [['text', '13/7/2025'], 'ED-0005', 'Kas Masuk Joki Tugas', 'Pendapatan', 'Excel', 200000, 0, 'Transfer', 3200000],
        ];

        $ss = new Spreadsheet();
        $ws = $ss->getActiveSheet()->setTitle('Kas 2025');
        $ws->fromArray(['Tanggal', 'No. Transaksi', 'Deskripsi', 'Kategori', 'Sub Kategori', 'Uang Masuk (Rp.)', 'Uang Keluar (Rp.)', 'Metode Bayar', 'Saldo Akhir'], null, 'A1');

        foreach ($rows as $i => $r) {
            $line = $i + 2;
            $d = array_shift($r);
            $ws->fromArray($r, null, 'B' . $line);
            if ($d[0] === 'cell') {
                $ws->setCellValue('A' . $line, ExcelDate::formattedPHPToExcel($d[1], $d[2], $d[3]));
                $ws->getStyle('A' . $line)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
            } else {
                $ws->setCellValueExplicit('A' . $line, $d[1], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            }
        }

        $pajak = $ss->createSheet()->setTitle('Pajak 2025');
        $pajak->fromArray([['PPH23'], ['BULAN', 'PENDAPATAN'], ['Mei', 5250000]], null, 'B1');

        $path = tempnam(sys_get_temp_dir(), 'kas') . '.xlsx';
        (new Xlsx($ss))->save($path);

        return $this->tmp[] = $path;
    }

    public function test_parse_tanggal_tertukar_teks_dan_sheet_pajak_diabaikan(): void
    {
        $this->kategori();
        $res = app(FinanceImportService::class)->parse($this->xlsx());
        $rows = $res['rows'];

        $this->assertSame('Kas 2025', $res['sheet']);
        $this->assertCount(5, $rows);
        $this->assertSame(['2025-05-02', '2025-05-27', '2025-06-28', '2025-07-11', '2025-07-13'], array_column($rows, 'date'));
        $this->assertSame(5000000.0, $rows[1]['amount']);       // "5.000.000" gaya Indonesia
        $this->assertSame('keluar', $rows[2]['type']);
        $this->assertSame('QRIS', $rows[2]['payment_method']);
        $this->assertSame('Kas Masuk Joki Tugas - Mas Dwi', $rows[0]['description']);
        $this->assertSame('Gaji Dev', $rows[3]['description']);  // "Kas Keluar" generik -> sub kategori
    }

    public function test_mode_asis_tidak_menukar_tanggal(): void
    {
        $res = app(FinanceImportService::class)->parse($this->xlsx(), 'asis');
        $this->assertSame('2025-02-05', $res['rows'][0]['date']);
    }

    public function test_pratinjau_tidak_menyimpan_apa_pun_lalu_konfirmasi_menyimpan(): void
    {
        $this->kategori();
        $sa = $this->superAdmin();
        $file = new UploadedFile($this->xlsx(), 'kas.xlsx', null, null, true);

        $res = $this->actingAs($sa)->post('/patra/finance/import/preview', ['file' => $file, 'date_mode' => 'auto']);
        $res->assertOk()->assertSee('Pratinjau');
        $this->assertSame(0, FinanceTransaction::count());

        $token = session('finance_import.token');
        $this->actingAs($sa)->post('/patra/finance/import', ['token' => $token])->assertRedirect('/patra/finance');

        $this->assertSame(5, FinanceTransaction::count());
        $t = FinanceTransaction::where('transaction_number', 'ED-0003')->first();
        $this->assertSame('2025-06-28', $t->date->toDateString());
        $this->assertSame('Hosting & Domain', $t->category->name);
        $this->assertSame('Gaji Developer', FinanceTransaction::where('transaction_number', 'ED-0004')->first()->category->name);
        $this->assertSame($sa->id, $t->created_by);
    }

    public function test_impor_ulang_melewati_duplikat(): void
    {
        $this->kategori();
        $sa = $this->superAdmin();
        foreach ([1, 2] as $_) {
            $file = new UploadedFile($this->xlsx(), 'kas.xlsx', null, null, true);
            $this->actingAs($sa)->post('/patra/finance/import/preview', ['file' => $file, 'date_mode' => 'auto']);
            $this->actingAs($sa)->post('/patra/finance/import', ['token' => session('finance_import.token')]);
        }
        $this->assertSame(5, FinanceTransaction::count());
    }

    public function test_periode_tertutup_dilewati(): void
    {
        $this->kategori();
        FinancePeriod::create(['period' => '2025-05', 'is_closed' => true]);
        $rows = app(FinanceImportService::class);
        $annotated = $rows->annotate($rows->parse($this->xlsx())['rows']);

        $this->assertSame('skip', $annotated[0]['status']);
        $this->assertSame('skip', $annotated[1]['status']);
        $this->assertSame('ok', $annotated[2]['status']);
    }

    public function test_baris_tidak_valid_ditandai_error(): void
    {
        $this->kategori();
        $svc = app(FinanceImportService::class);
        $path = $this->xlsx([
            [['text', '31/02/2025'], 'ED-0001', 'x', '', '', 100, 0, 'Transfer', null],
            [['text', '01/03/2025'], 'ED-0002', 'x', '', '', 100, 50, 'Transfer', null],
            [['text', '01/03/2025'], 'ED-0003', 'x', '', '', 0, 0, 'Transfer', null],
        ]);
        $rows = $svc->annotate($svc->parse($path)['rows']);

        $this->assertSame(['error', 'error', 'error'], array_column($rows, 'status'));
        $this->assertSame(0, FinanceTransaction::count());
    }

    public function test_hanya_superadmin_dan_format_file_divalidasi(): void
    {
        $this->actingAs($this->admin())->get('/patra/finance/import')->assertForbidden();

        $sa = $this->superAdmin();
        $this->actingAs($sa)->get('/patra/finance/import')->assertOk();

        $bad = UploadedFile::fake()->create('x.pdf', 10, 'application/pdf');
        $this->actingAs($sa)->post('/patra/finance/import/preview', ['file' => $bad, 'date_mode' => 'auto'])
            ->assertSessionHasErrors('file');
    }

    public function test_konfirmasi_tanpa_pratinjau_ditolak(): void
    {
        $this->actingAs($this->superAdmin())->post('/patra/finance/import', ['token' => 'palsu'])
            ->assertRedirect('/patra/finance/import');
        $this->assertSame(0, FinanceTransaction::count());
    }
}

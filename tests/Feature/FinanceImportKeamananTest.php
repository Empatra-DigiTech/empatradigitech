<?php

namespace Tests\Feature;

use App\Exceptions\UserFacingException;
use App\Services\FinanceImportService;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

/** A1-A3: pembaca file dibatasi, formula tidak dievaluasi, dan pemakaian memori dibatasi. */
class FinanceImportKeamananTest extends TestCase
{
    private array $tmp = [];

    protected function tearDown(): void
    {
        foreach ($this->tmp as $f) {
            @unlink($f);
        }
        parent::tearDown();
    }

    private function path(string $ext = ''): string
    {
        $p = tempnam(sys_get_temp_dir(), 'imp') . $ext;

        return $this->tmp[] = $p;
    }

    private function sheet(int $rows = 3): Spreadsheet
    {
        $ss = new Spreadsheet();
        $ws = $ss->getActiveSheet()->setTitle('Kas');
        $ws->fromArray(['Tanggal', 'Deskripsi', 'Uang Masuk (Rp.)', 'Uang Keluar (Rp.)'], null, 'A1');
        for ($i = 0; $i < $rows; $i++) {
            $ws->fromArray(['2025-06-' . str_pad((string) (($i % 28) + 1), 2, '0', STR_PAD_LEFT), 'Transaksi ' . $i, 1000 + $i, 0], null, 'A' . ($i + 2));
        }

        return $ss;
    }

    private function parse(string $path): array
    {
        return app(FinanceImportService::class)->parse($path, FinanceImportService::MODE_ASIS);
    }

    // ---------- A1
    public function test_xlsx_normal_tetap_terbaca(): void
    {
        $p = $this->path('.xlsx');
        (new Xlsx($this->sheet(3)))->save($p);

        $hasil = $this->parse($p);

        $this->assertCount(3, $hasil['rows']);
        $this->assertSame(1000.0, $hasil['rows'][0]['amount']);
    }

    public function test_csv_tetap_terbaca(): void
    {
        $p = $this->path('.csv');
        file_put_contents($p, "Tanggal,Deskripsi,Uang Masuk (Rp.),Uang Keluar (Rp.)\n2025-06-01,Penjualan,5000,0\n");

        $hasil = $this->parse($p);

        $this->assertCount(1, $hasil['rows']);
        $this->assertSame(5000.0, $hasil['rows'][0]['amount']);
    }

    public function test_html_yang_menyamar_jadi_csv_ditolak(): void
    {
        $p = $this->path('.csv');
        file_put_contents($p, '<html><body><table><tr><td>Tanggal</td><td>Deskripsi</td><td>Uang Masuk (Rp.)</td><td>Uang Keluar (Rp.)</td></tr>'
            . '<tr><td>2025-06-01</td><td>Dari HTML</td><td>5000</td><td>0</td></tr></table></body></html>');

        $this->expectException(UserFacingException::class);

        $this->parse($p);
    }

    public function test_ods_ditolak_karena_bukan_format_yang_diizinkan(): void
    {
        $p = $this->path('.txt');
        IOFactory::createWriter($this->sheet(2), 'Ods')->save($p);

        $this->expectException(UserFacingException::class);

        $this->parse($p);
    }

    public function test_html_hasil_writer_ditolak(): void
    {
        $p = $this->path('.txt');
        IOFactory::createWriter($this->sheet(2), 'Html')->save($p);

        $this->expectException(UserFacingException::class);

        $this->parse($p);
    }

    // ---------- A2
    public function test_formula_memakai_nilai_cache_excel_bukan_dievaluasi_ulang(): void
    {
        $ss = $this->sheet(1);
        $ss->getActiveSheet()->setCellValue('C2', '=1+1');
        $p = $this->path('.xlsx');
        (new Xlsx($ss))->save($p);

        // Ubah nilai cache di dalam file menjadi 7777: bila terbaca 7777, formula tidak dihitung ulang.
        $zip = new \ZipArchive();
        $zip->open($p);
        $xml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $this->assertStringContainsString('<f>1+1</f><v>2</v>', $xml, 'asumsi struktur xlsx');
        $zip->addFromString('xl/worksheets/sheet1.xml', str_replace('<f>1+1</f><v>2</v>', '<f>1+1</f><v>7777</v>', $xml));
        $zip->close();

        $this->assertSame(7777.0, $this->parse($p)['rows'][0]['amount']);
    }

    public function test_formula_tanpa_nilai_cache_menandai_baris_error_bukan_menggagalkan_seluruh_file(): void
    {
        $ss = $this->sheet(3);
        $ss->getActiveSheet()->setCellValue('C3', '=FUNGSI_TIDAK_ADA(1)');
        $p = $this->path('.xlsx');
        $writer = new Xlsx($ss);
        $writer->setPreCalculateFormulas(false);
        $writer->save($p);

        $rows = $this->parse($p)['rows'];

        $this->assertCount(3, $rows);
        $this->assertNull($rows[0]['error']);
        $this->assertStringContainsString('formula', strtolower((string) $rows[1]['error']));
        $this->assertNull($rows[2]['error']);
    }

    public function test_baris_template_kosong_dengan_formula_hasil_kosong_dilewati_bukan_error(): void
    {
        $ss = $this->sheet(2);
        $ws = $ss->getActiveSheet();
        $ws->setCellValue('E1', 'Saldo Akhir');
        // Baris template: tidak ada tanggal/nominal, hanya formula yang hasilnya teks kosong (umum di buku kas).
        $ws->setCellValue('E4', '=IF(A4="","","ada")');
        $p = $this->path('.xlsx');
        (new Xlsx($ss))->save($p);

        $rows = $this->parse($p)['rows'];

        $this->assertCount(2, $rows);
    }

    // ---------- A3
    public function test_file_lebih_dari_batas_baris_tetap_ditolak_dengan_pesan_jelas(): void
    {
        $p = $this->path('.xlsx');
        (new Xlsx($this->sheet(FinanceImportService::MAX_ROWS + 100)))->save($p);

        try {
            $this->parse($p);
            $this->fail('Seharusnya ditolak karena melebihi batas baris');
        } catch (UserFacingException $e) {
            $this->assertStringContainsString((string) FinanceImportService::MAX_ROWS, $e->getMessage());
        }
    }

    public function test_file_tepat_di_batas_baris_diterima_penuh(): void
    {
        $p = $this->path('.xlsx');
        (new Xlsx($this->sheet(FinanceImportService::MAX_ROWS)))->save($p);

        $this->assertCount(FinanceImportService::MAX_ROWS, $this->parse($p)['rows']);
    }

    public function test_xlsx_yang_membengkak_setelah_dibuka_ditolak_sebelum_dimuat(): void
    {
        $p = $this->path('.xlsx');
        $zip = new \ZipArchive();
        $zip->open($p, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->addFromString('[Content_Types].xml', '<Types/>');
        $zip->addFromString('xl/besar.bin', str_repeat('0', 60 * 1024 * 1024));
        $zip->close();

        $this->assertLessThan(1024 * 1024, filesize($p), 'asumsi: terkompresi sangat kecil');

        try {
            $this->parse($p);
            $this->fail('Seharusnya ditolak');
        } catch (UserFacingException $e) {
            $this->assertStringContainsString('terlalu besar', strtolower($e->getMessage()));
        }
    }

    public function test_kolom_di_luar_batas_tidak_dimuat(): void
    {
        $ss = $this->sheet(2);
        $ss->getActiveSheet()->setCellValue('ZZ2', 'sampah di kolom jauh');
        $p = $this->path('.xlsx');
        (new Xlsx($ss))->save($p);

        $this->assertCount(2, $this->parse($p)['rows']);
    }
}

<?php

namespace App\Services;

use App\Exceptions\UserFacingException;
use App\Models\FinanceCategory;
use App\Models\FinancePeriod;
use App\Models\FinanceTransaction;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Membaca pembukuan Excel (format "KAS CV EMPATRA DIGITECH") menjadi baris transaksi siap impor.
 *
 * Kolom yang dikenali (urutan bebas, huruf besar/kecil bebas):
 *   Tanggal | No. Transaksi | Deskripsi | Kategori | Sub Kategori | Uang Masuk (Rp.) | Uang Keluar (Rp.) | Metode Bayar | Saldo Akhir
 * Hanya Tanggal + (Uang Masuk / Uang Keluar) yang wajib.
 */
class FinanceImportService
{
    public const MAX_ROWS = 2000;

    public const MODE_AUTO = 'auto';
    public const MODE_SWAP = 'swap';
    public const MODE_ASIS = 'asis';

    private const HEADER_ALIASES = [
        'date' => ['tanggal', 'tgl', 'date'],
        'number' => ['notransaksi', 'nomortransaksi', 'notrx', 'nomor', 'no'],
        'description' => ['deskripsi', 'keterangan', 'uraian', 'description'],
        'category' => ['kategori', 'category'],
        'sub' => ['subkategori', 'subcategory'],
        'in' => ['uangmasukrp', 'uangmasuk', 'masuk', 'debit', 'pemasukan'],
        'out' => ['uangkeluarrp', 'uangkeluar', 'keluar', 'kredit', 'pengeluaran'],
        'method' => ['metodebayar', 'metode', 'metodepembayaran'],
        'balance' => ['saldoakhir', 'saldo'],
        'notes' => ['catatan', 'notes'],
    ];

    /**
     * @return array{rows: array<int,array>, sheet: string, summary: array, warnings: array<int,string>}
     */
    public function parse(string $path, string $dateMode = self::MODE_AUTO): array
    {
        try {
            $reader = IOFactory::createReaderForFile($path);
            $reader->setReadDataOnly(false);
            $spreadsheet = $reader->load($path);
        } catch (\Throwable $e) {
            throw new UserFacingException('File tidak bisa dibaca. Pastikan formatnya .xlsx / .xls / .csv yang valid.');
        }

        [$sheet, $headerRow, $map] = $this->locateTable($spreadsheet->getAllSheets());

        $raw = [];
        $highest = $sheet->getHighestDataRow();
        if ($highest - $headerRow > self::MAX_ROWS) {
            throw new UserFacingException('Maksimal ' . self::MAX_ROWS . ' baris per impor. Pecah file menjadi beberapa bagian.');
        }

        for ($r = $headerRow + 1; $r <= $highest; $r++) {
            // Sel tanggal dibaca PERTAMA: PhpSpreadsheet membatalkan objek sel lama setelah getCell() lain,
            // sehingga deteksi format tanggal gagal bila sel disimpan lebih lama.
            $date = isset($map['date']) ? $this->rawDate($sheet->getCell([$map['date'], $r])) : ['kind' => 'none'];
            $value = fn (string $key) => isset($map[$key]) ? $sheet->getCell([$map[$key], $r])->getCalculatedValue() : null;

            $inVal = $this->amount($value('in'));
            $outVal = $this->amount($value('out'));

            // Baris kosong / baris total tanpa tanggal dan tanpa nominal dilewati diam-diam.
            if ($date['kind'] === 'none' && ! $inVal && ! $outVal) {
                continue;
            }

            $raw[] = [
                'excel_row' => $r,
                'date' => $date,
                'number' => $this->text($value('number')),
                'description' => $this->text($value('description')),
                'category' => $this->text($value('category')),
                'sub' => $this->text($value('sub')),
                'in' => $inVal,
                'out' => $outVal,
                'method' => $this->text($value('method')),
                'balance' => $this->amount($value('balance'), true),
                'notes' => $this->text($value('notes')),
            ];
        }

        if ($raw === []) {
            throw new UserFacingException('Tidak ada baris transaksi yang terbaca di sheet "' . $sheet->getTitle() . '".');
        }

        $swap = $this->decideSwap($raw, $dateMode);

        $rows = [];
        $warnings = [];
        foreach ($raw as $i => $r) {
            $row = $this->buildRow($r, $swap);
            $rows[] = $row;
        }

        if ($swap) {
            $warnings[] = 'Tanggal bertipe tanggal-Excel dibaca dengan hari dan bulan ditukar (Excel menyimpannya sebagai bulan/hari). Cek kolom Tanggal di pratinjau.';
        }

        return [
            'rows' => $rows,
            'sheet' => $sheet->getTitle(),
            'swapped' => $swap,
            'warnings' => $warnings,
        ];
    }

    /**
     * Tambahkan status per baris terhadap isi database: duplikat, periode tertutup, kategori.
     * Baris berstatus "ok" siap diimpor; "skip" dilewati; "error" harus diperbaiki di Excel.
     */
    public function annotate(array $rows): array
    {
        $categories = FinanceCategory::with('parent')->where('is_active', true)->get();
        $existingNumbers = FinanceTransaction::withTrashed()->pluck('transaction_number')->map(fn ($n) => strtoupper($n))->flip()->all();
        $closed = array_flip(FinancePeriod::closedPeriods());
        $seen = [];

        foreach ($rows as $i => &$row) {
            $row['category_id'] = $row['category_id'] ?? $this->guessCategory($row, $categories)?->id;
            $row['status'] = $row['error'] ? 'error' : 'ok';
            $row['reason'] = $row['error'];

            if ($row['status'] !== 'ok') {
                continue;
            }

            $period = substr($row['date'], 0, 7);
            if (isset($closed[$period])) {
                $row['status'] = 'skip';
                $row['reason'] = "Periode {$period} sudah ditutup";
                continue;
            }

            if ($row['number'] !== '') {
                $key = strtoupper($row['number']);
                if (isset($existingNumbers[$key]) || isset($seen[$key])) {
                    $row['status'] = 'skip';
                    $row['reason'] = 'No. Transaksi sudah ada';
                    continue;
                }
                $seen[$key] = true;
            } elseif (FinanceTransaction::where('date', $row['date'])->where('type', $row['type'])
                ->where('amount', $row['amount'])->where('description', $row['description'])->exists()) {
                $row['status'] = 'skip';
                $row['reason'] = 'Transaksi yang sama sudah ada';
                continue;
            }

            if (! $row['category_id']) {
                $row['reason'] = 'Pilih kategori';
            }
        }
        unset($row);

        return $rows;
    }

    /** Ringkasan untuk pratinjau, termasuk pemeriksaan silang dengan kolom "Saldo Akhir" di Excel. */
    public function summarize(array $rows): array
    {
        $ok = array_filter($rows, fn ($r) => $r['status'] === 'ok');
        $in = array_sum(array_map(fn ($r) => $r['type'] === 'masuk' ? $r['amount'] : 0, $ok));
        $out = array_sum(array_map(fn ($r) => $r['type'] === 'keluar' ? $r['amount'] : 0, $ok));

        // Selisih kolom Saldo Akhir Excel dengan hitungan sendiri (hanya bila semua baris valid & terbaca).
        $mismatch = [];
        $running = null;
        foreach ($rows as $r) {
            if ($r['error'] || $r['balance'] === null) {
                $running = null;
                continue;
            }
            $delta = $r['type'] === 'masuk' ? $r['amount'] : -$r['amount'];
            if ($running !== null && abs(($running + $delta) - $r['balance']) > 0.5) {
                $mismatch[] = $r['number'] ?: ('baris ' . $r['excel_row']);
            }
            $running = $r['balance'];
        }

        return [
            'total' => count($rows),
            'ok' => count($ok),
            'skip' => count(array_filter($rows, fn ($r) => $r['status'] === 'skip')),
            'error' => count(array_filter($rows, fn ($r) => $r['status'] === 'error')),
            'needs_category' => count(array_filter($ok, fn ($r) => ! $r['category_id'])),
            'in' => $in,
            'out' => $out,
            'balance_mismatch' => $mismatch,
        ];
    }

    // ------------------------------------------------------------------ internal

    /** Pilih sheet + baris header yang memuat kolom Tanggal dan Uang Masuk/Keluar. */
    private function locateTable(array $sheets): array
    {
        foreach ($sheets as $sheet) {
            $maxRow = min(10, $sheet->getHighestDataRow());
            $maxCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($sheet->getHighestDataColumn());

            for ($r = 1; $r <= $maxRow; $r++) {
                $map = [];
                for ($c = 1; $c <= $maxCol; $c++) {
                    $key = $this->normalizeHeader($sheet->getCell([$c, $r])->getValue());
                    if ($key === '') {
                        continue;
                    }
                    foreach (self::HEADER_ALIASES as $field => $aliases) {
                        if (! isset($map[$field]) && in_array($key, $aliases, true)) {
                            $map[$field] = $c;
                        }
                    }
                }
                if (isset($map['date']) && (isset($map['in']) || isset($map['out']))) {
                    return [$sheet, $r, $map];
                }
            }
        }

        throw new UserFacingException('Kolom tidak dikenali. Baris judul harus memuat minimal: Tanggal, Uang Masuk, dan Uang Keluar.');
    }

    private function normalizeHeader($v): string
    {
        return preg_replace('/[^a-z0-9]/', '', strtolower((string) $v));
    }

    private function text($v): string
    {
        return trim((string) ($v ?? ''));
    }

    /** Angka dari sel: numerik apa adanya, teks gaya Indonesia ("1.700.697", "Rp 5.000,50") dikonversi. */
    private function amount($v, bool $nullable = false): ?float
    {
        if ($v === null || $v === '') {
            return $nullable ? null : 0.0;
        }
        if (is_int($v) || is_float($v)) {
            return (float) $v;
        }

        $s = preg_replace('/[^0-9,.\-]/', '', (string) $v);
        if ($s === '' || $s === '-') {
            return $nullable ? null : 0.0;
        }

        $hasDot = str_contains($s, '.');
        $hasComma = str_contains($s, ',');
        if ($hasDot && $hasComma) {
            $dec = strrpos($s, ',') > strrpos($s, '.') ? ',' : '.';
            $thou = $dec === ',' ? '.' : ',';
            $s = str_replace($dec, '#', str_replace($thou, '', $s));
            $s = str_replace('#', '.', $s);
        } elseif ($hasDot || $hasComma) {
            $sep = $hasDot ? '.' : ',';
            $isThousand = preg_match('/^-?\d{1,3}(\\' . $sep . '\d{3})+$/', $s) === 1;
            $s = $isThousand ? str_replace($sep, '', $s) : str_replace($sep, '.', $s);
        }

        return is_numeric($s) ? (float) $s : ($nullable ? null : 0.0);
    }

    /** Nilai mentah tanggal: ['kind' => 'cell'|'text', 'y','m','d'] atau teks asli. */
    private function rawDate($cell): array
    {
        if ($cell === null) {
            return ['kind' => 'none'];
        }
        $v = $cell->getCalculatedValue();

        if (is_numeric($v) && ExcelDate::isDateTime($cell)) {
            $d = ExcelDate::excelToDateTimeObject((float) $v);

            return ['kind' => 'cell', 'y' => (int) $d->format('Y'), 'm' => (int) $d->format('n'), 'd' => (int) $d->format('j')];
        }

        $s = trim((string) $v);
        if ($s === '') {
            return ['kind' => 'none'];
        }

        // Teks tanggal gaya Indonesia: d/m/Y, d-m-Y, d.m.Y (tahun 2 atau 4 digit).
        if (preg_match('#^(\d{1,2})[/\-.](\d{1,2})[/\-.](\d{2}|\d{4})$#', $s, $m)) {
            $y = (int) $m[3] < 100 ? 2000 + (int) $m[3] : (int) $m[3];

            return ['kind' => 'text', 'y' => $y, 'm' => (int) $m[2], 'd' => (int) $m[1]];
        }
        if (preg_match('#^(\d{4})-(\d{1,2})-(\d{1,2})#', $s, $m)) {
            return ['kind' => 'text', 'y' => (int) $m[1], 'm' => (int) $m[2], 'd' => (int) $m[3]];
        }

        return ['kind' => 'invalid', 'text' => $s];
    }

    /**
     * Sel Excel yang dikenali sebagai tanggal sering sudah tertukar hari<->bulan (diketik dd/mm tapi dibaca mm/dd),
     * sedangkan yang tidak bisa dikenali (hari > 12) tersimpan sebagai teks dd/mm/yyyy yang benar.
     * Mode auto memilih tafsir yang urutan tanggalnya paling kronologis sesuai urutan baris.
     */
    private function decideSwap(array $raw, string $mode): bool
    {
        if ($mode === self::MODE_SWAP) {
            return true;
        }
        if ($mode === self::MODE_ASIS) {
            return false;
        }

        $inversions = function (bool $swap) use ($raw): int {
            $prev = null;
            $count = 0;
            foreach ($raw as $r) {
                $d = $this->resolveDate($r['date'], $swap);
                if (! $d) {
                    continue;
                }
                if ($prev && $d < $prev) {
                    $count++;
                }
                $prev = $d;
            }

            return $count;
        };

        return $inversions(true) < $inversions(false);
    }

    private function resolveDate(array $raw, bool $swap): ?string
    {
        if (! in_array($raw['kind'], ['cell', 'text'], true)) {
            return null;
        }
        [$y, $m, $d] = [$raw['y'], $raw['m'], $raw['d']];
        if ($raw['kind'] === 'cell' && $swap) {
            [$m, $d] = [$d, $m];
        }

        return checkdate($m, $d, $y) ? sprintf('%04d-%02d-%02d', $y, $m, $d) : null;
    }

    private function buildRow(array $r, bool $swap): array
    {
        $error = null;
        $date = $this->resolveDate($r['date'], $swap);
        if (! $date) {
            $error = $r['date']['kind'] === 'invalid' ? 'Format tanggal tidak dikenali: ' . $r['date']['text'] : 'Tanggal kosong / tidak valid';
        }

        $in = $r['in'] ?? 0.0;
        $out = $r['out'] ?? 0.0;
        if (! $error && $in > 0 && $out > 0) {
            $error = 'Uang Masuk dan Uang Keluar terisi bersamaan';
        } elseif (! $error && $in <= 0 && $out <= 0) {
            $error = 'Jumlah masuk/keluar kosong atau nol';
        }
        $type = $in > 0 ? 'masuk' : 'keluar';
        $amount = $in > 0 ? $in : $out;

        // Deskripsi: "Kas Masuk"/"Kas Keluar"/kosong dianggap generik, pakai Sub Kategori; selain itu "Deskripsi - Sub".
        $desc = $r['description'];
        $generic = $desc === '' || preg_match('/^kas\s+(masuk|keluar)$/i', $desc) === 1;
        $description = $generic ? $r['sub'] : ($r['sub'] !== '' ? $desc . ' - ' . $r['sub'] : $desc);
        if ($description === '') {
            $description = $r['category'] !== '' ? $r['category'] : '';
        }
        if (! $error && $description === '') {
            $error = 'Deskripsi kosong';
        }
        $description = mb_substr($description, 0, 255);

        $method = match (true) {
            preg_match('/^(transfer|tf|trf)$/i', $r['method']) === 1 => 'Transfer',
            preg_match('/^(tunai|cash)$/i', $r['method']) === 1 => 'Tunai',
            strtolower($r['method']) === 'qris' => 'QRIS',
            default => $r['method'] === '' ? 'Transfer' : 'Lainnya',
        };

        return [
            'excel_row' => $r['excel_row'],
            'date' => $date,
            'number' => $r['number'],
            'description' => $description,
            'type' => $type,
            'amount' => $amount,
            'payment_method' => $method,
            'notes' => $r['notes'] !== '' ? $r['notes'] : null,
            'hint' => trim($r['category'] . ' ' . $r['sub']),
            'balance' => $r['balance'],
            'category_id' => null,
            'error' => $error,
        ];
    }

    /** Tebak kategori dari kata kunci deskripsi/sub kategori; kembalikan null bila kategori master tidak ada. */
    private function guessCategory(array $row, $categories): ?FinanceCategory
    {
        $text = strtolower($row['description'] . ' ' . $row['hint']);
        $has = fn (string ...$words) => (bool) array_filter($words, fn ($w) => str_contains($text, $w));

        if ($row['type'] === 'masuk') {
            [$parent, $child] = match (true) {
                $has('joki') => ['Pendapatan Jasa / Proyek', 'Joki Tugas'],
                $has('flyer', 'logo', 'desain') => ['Pendapatan Jasa / Proyek', 'Desain / Flyer'],
                $has('website', 'web ') => ['Pendapatan Jasa / Proyek', 'Website'],
                $has('aplikasi', 'lms', 'app') => ['Pendapatan Jasa / Proyek', 'Aplikasi'],
                default => ['Pendapatan Jasa / Proyek', 'Lainnya'],
            };
        } else {
            [$parent, $child] = match (true) {
                $has('gaji') && $has('broker', 'sosmed') => ['Gaji & Bonus', 'Gaji Broker / Sosmed'],
                $has('gaji') => ['Gaji & Bonus', 'Gaji Developer'],
                $has('bonus') => ['Gaji & Bonus', 'Bonus'],
                $has('hosting', 'domain', 'perpanjangan') => ['Hosting & Domain', null],
                $has('biaya admin') => ['Operasional', 'Biaya Admin Bank'],
                $has('lampu', 'kain', 'atk') => ['Operasional', 'ATK & Perlengkapan'],
                $has('follower', 'banner', 'iklan', 'ads') => ['Marketing & Iklan', null],
                default => ['Lainnya', null],
            };
        }

        $found = $categories->first(fn ($c) => $c->type === $row['type'] && $c->name === ($child ?? $parent)
            && ($child === null ? $c->parent_id === null : $c->parent?->name === $parent));

        return $found;
    }
}

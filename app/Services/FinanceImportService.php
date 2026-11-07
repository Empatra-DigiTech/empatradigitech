<?php

namespace App\Services;

use App\Exceptions\UserFacingException;
use App\Models\FinanceCategory;
use App\Models\FinancePeriod;
use App\Models\FinanceTransaction;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

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
    public const MIN_YEAR = 2000;
    public const MAX_YEAR = 2100;
    public const MAX_NUMBER_LENGTH = 50;
    public const MAX_NOTES_LENGTH = 2000;

    /** Baris judul dicari di 10 baris pertama (lihat locateTable). */
    private const HEADER_SCAN_ROWS = 10;

    /** Kolom di luar ini (kolom A sampai AN) tidak dimuat. */
    private const MAX_COLUMNS = 40;

    /** Batas ukuran isi file .xlsx setelah dibuka (terkompresi), melindungi dari "zip bomb". */
    private const MAX_UNCOMPRESSED_BYTES = 50 * 1024 * 1024;
    private const MAX_ZIP_ENTRIES = 2000;

    /** Format yang boleh dibaca. Format lain (HTML, XML, SYLK, ODS, Gnumeric) sengaja ditolak. */
    private const ALLOWED_READERS = [IOFactory::READER_XLSX, IOFactory::READER_XLS, IOFactory::READER_CSV];

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
     * @return array{rows: array<int,array>, sheet: string, swapped: bool, warnings: array<int,string>}
     */
    public function parse(string $path, string $dateMode = self::MODE_AUTO): array
    {
        $spreadsheet = $this->load($path);

        try {
            return $this->parseSpreadsheet($spreadsheet, $dateMode);
        } finally {
            // Bebaskan memori sheet segera; PhpSpreadsheet menyimpan referensi melingkar antar objek.
            $spreadsheet->disconnectWorksheets();
        }
    }

    /**
     * Muat file dengan pembaca yang dibatasi ke xlsx/xls/csv. Format ditentukan dari ISI file (bukan nama),
     * tetapi hanya di antara tiga format itu, jadi file HTML/XML/ODS yang disamarkan jadi .csv/.txt ditolak.
     * Hanya sel dalam batas baris/kolom yang dimuat.
     */
    private function load(string $path): Spreadsheet
    {
        $this->assertSafeArchiveSize($path);

        try {
            $reader = IOFactory::createReader(IOFactory::identify($path, self::ALLOWED_READERS));
            $reader->setReadDataOnly(false);
            $reader->setReadEmptyCells(false);
            // +1 baris cadangan: file yang melebihi MAX_ROWS tetap terdeteksi dan ditolak, bukan terpotong diam-diam.
            $reader->setReadFilter(new FinanceImportReadFilter(self::HEADER_SCAN_ROWS + self::MAX_ROWS + 1, self::MAX_COLUMNS));

            return $reader->load($path);
        } catch (\Throwable $e) {
            throw new UserFacingException('File tidak bisa dibaca. Pastikan formatnya .xlsx / .xls / .csv yang valid.');
        }
    }

    /** File .xlsx adalah zip kecil yang bisa membengkak berpuluh-puluh MB saat dibuka. */
    private function assertSafeArchiveSize(string $path): void
    {
        if (! class_exists(\ZipArchive::class)) {
            return;
        }

        $zip = new \ZipArchive();
        if ($zip->open($path) !== true) {
            return; // bukan zip (xls / csv)
        }

        try {
            $total = 0;
            if ($zip->numFiles > self::MAX_ZIP_ENTRIES) {
                throw new UserFacingException('Isi file terlalu besar atau tidak wajar. Simpan ulang sebagai .xlsx biasa dari Excel.');
            }
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $total += (int) ($zip->statIndex($i)['size'] ?? 0);
                if ($total > self::MAX_UNCOMPRESSED_BYTES) {
                    throw new UserFacingException('Isi file terlalu besar setelah dibuka (maksimal ' . (self::MAX_UNCOMPRESSED_BYTES / 1024 / 1024) . ' MB). Simpan ulang sebagai .xlsx biasa dari Excel.');
                }
            }
        } finally {
            $zip->close();
        }
    }

    private function parseSpreadsheet(Spreadsheet $spreadsheet, string $dateMode): array
    {
        [$sheet, $headerRow, $map] = $this->locateTable($spreadsheet->getAllSheets());

        $raw = [];
        $highest = $sheet->getHighestDataRow();
        if ($highest - $headerRow > self::MAX_ROWS) {
            throw new UserFacingException('Maksimal ' . self::MAX_ROWS . ' baris per impor. Pecah file menjadi beberapa bagian.');
        }

        for ($r = $headerRow + 1; $r <= $highest; $r++) {
            // Sel tanggal dibaca PERTAMA: PhpSpreadsheet membatalkan objek sel lama setelah getCell() lain,
            // sehingga deteksi format tanggal gagal bila sel disimpan lebih lama.
            $formulaMissing = false;
            $date = isset($map['date']) ? $this->rawDate($sheet->getCell([$map['date'], $r]), $formulaMissing) : ['kind' => 'none'];
            // Closure biasa (bukan fn): $formulaMissing harus ikut berubah di luar, arrow function menangkap nilai saja.
            $value = function (string $key) use ($map, $sheet, $r, &$formulaMissing) {
                return isset($map[$key]) ? $this->cellValue($sheet->getCell([$map[$key], $r]), $formulaMissing) : null;
            };

            $inRaw = $value('in');
            $outRaw = $value('out');
            $inVal = $this->amount($inRaw);
            $outVal = $this->amount($outRaw);

            // Baris kosong / baris total tanpa tanggal dan tanpa nominal dilewati diam-diam,
            // kecuali ada formula tanpa nilai: itu harus dilaporkan, bukan hilang.
            if ($date['kind'] === 'none' && ! $inVal && ! $outVal && ! $formulaMissing) {
                continue;
            }

            $raw[] = [
                'formula_missing' => $formulaMissing,
                'excel_row' => $r,
                'date' => $date,
                'number' => $this->text($value('number')),
                'description' => $this->text($value('description')),
                'category' => $this->text($value('category')),
                'sub' => $this->text($value('sub')),
                'in' => $inVal,
                'out' => $outVal,
                'amount_unsupported' => $this->unsupportedAmount($inRaw) ?? $this->unsupportedAmount($outRaw),
                'method' => $this->text($value('method')),
                'balance' => $this->amount($value('balance'), true),
                'notes' => $this->text($value('notes')),
            ];
        }

        if ($raw === []) {
            throw new UserFacingException('Tidak ada baris transaksi yang terbaca di sheet "' . $sheet->getTitle() . '".');
        }

        $swap = $this->decideSwap($raw, $dateMode);
        $dateWarning = $dateMode === self::MODE_AUTO ? $this->dateAmbiguityWarning($raw) : null;

        $rows = [];
        $warnings = [];
        foreach ($raw as $r) {
            $rows[] = $this->buildRow($r, $swap);
        }

        if ($swap) {
            $warnings[] = 'Tanggal bertipe tanggal-Excel dibaca dengan hari dan bulan ditukar (Excel menyimpannya sebagai bulan/hari). Cek kolom Tanggal di pratinjau.';
        }

        if ($dateWarning !== null) {
            $warnings[] = $dateWarning;
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
        // Satu query untuk semua baris (bukan satu query per baris): hanya nomor yang ada di file yang dicari.
        $existingNumbers = $this->existingNumbers(array_column(array_filter($rows, fn ($r) => ! $r['error'] && $r['number'] !== ''), 'number'));
        $existingUnnumbered = $this->existingTransactionKeys(array_filter($rows, fn ($r) => ! $r['error'] && $r['number'] === ''));
        $closed = array_flip(FinancePeriod::closedPeriods());
        $seen = [];
        $seenUnnumbered = [];

        foreach ($rows as &$row) {
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
            } else {
                $key = $this->transactionKey($row);
                if (($existingUnnumbered[$key] ?? null) === 'active') {
                    $row['status'] = 'skip';
                    $row['reason'] = 'Transaksi yang sama sudah ada';
                    continue;
                }
                if (($existingUnnumbered[$key] ?? null) === 'trashed') {
                    $row['status'] = 'skip';
                    $row['reason'] = 'Transaksi yang sama ada di Sampah. Pulihkan dari menu Sampah bila memang diperlukan';
                    continue;
                }
                if (isset($seenUnnumbered[$key])) {
                    // Dua baris identik dalam satu file bisa sah (dua pembelian yang sama), jadi hanya diberi peringatan.
                    $row['warning'] = "Sama persis dengan baris Excel {$seenUnnumbered[$key]} di file ini";
                } else {
                    $seenUnnumbered[$key] = $row['excel_row'];
                }
            }

            if (! $row['category_id']) {
                $row['reason'] = 'Pilih kategori';
            }
        }
        unset($row);

        return $rows;
    }

    /**
     * Himpunan nomor (HURUF BESAR => true) dari daftar yang sudah dipakai transaksi, termasuk yang dihapus.
     * Dicari lewat WHERE IN per 500 nomor, bukan membaca seluruh tabel.
     *
     * @param  array<int,string>  $numbers
     * @return array<string,bool>
     */
    public function existingNumbers(array $numbers): array
    {
        $numbers = array_values(array_unique(array_filter($numbers, fn ($n) => $n !== '')));
        $found = [];

        foreach (array_chunk($numbers, 500) as $chunk) {
            // Varian huruf besar ikut dicari: perbandingan WHERE IN mengikuti collation database.
            $variants = array_values(array_unique(array_merge($chunk, array_map('strtoupper', $chunk))));

            foreach (FinanceTransaction::withTrashed()->whereIn('transaction_number', $variants)->pluck('transaction_number') as $n) {
                $found[strtoupper($n)] = true;
            }
        }

        return $found;
    }

    /** Kunci pembanding transaksi tanpa nomor: tanggal | jenis | nominal | deskripsi (tanpa beda huruf/spasi pinggir). */
    private function transactionKey(array $row): string
    {
        return substr((string) $row['date'], 0, 10) . '|' . $row['type'] . '|'
            . number_format((float) $row['amount'], 2, '.', '') . '|' . mb_strtolower(trim((string) $row['description']));
    }

    /**
     * Transaksi yang sudah ada dan cocok dengan baris tanpa nomor, dicari dengan SATU query pada rentang tanggal file.
     *
     * @param  array<int,array>  $rows
     * @return array<string,string> kunci => 'active'|'trashed'
     */
    private function existingTransactionKeys(array $rows): array
    {
        $dates = array_filter(array_column($rows, 'date'));
        if ($dates === []) {
            return [];
        }

        $found = [];
        // Termasuk transaksi di Sampah, sama seperti pemeriksaan baris bernomor.
        $query = FinanceTransaction::withTrashed()->toBase()
            ->select('date', 'type', 'amount', 'description', 'deleted_at')
            ->whereBetween('date', [min($dates), max($dates) . ' 23:59:59']);

        foreach ($query->cursor() as $t) {
            $key = $this->transactionKey(['date' => $t->date, 'type' => $t->type, 'amount' => $t->amount, 'description' => $t->description]);
            if (($found[$key] ?? null) !== 'active') {
                $found[$key] = $t->deleted_at === null ? 'active' : 'trashed';
            }
        }

        return $found;
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

    /**
     * Nilai sel. Formula TIDAK dihitung ulang: dipakai nilai terakhir yang disimpan Excel di dalam file.
     * File dari sumber tak tepercaya tidak boleh menjalankan mesin formula (lambat, dan satu formula rusak
     * menggagalkan seluruh impor). Formula tanpa nilai tersimpan ditandai lewat $formulaMissing.
     */
    private function cellValue($cell, bool &$formulaMissing)
    {
        if ($cell === null) {
            return null;
        }

        if ($cell->isFormula()) {
            $cached = $cell->getOldCalculatedValue();
            if ($cached === null) {
                $formulaMissing = true;
            }

            return $cached;
        }

        return $cell->getCalculatedValue();
    }

    private function text($v): string
    {
        return trim((string) ($v ?? ''));
    }

    /**
     * Teks angka yang ambigu dan tidak boleh ditebak: tanda kurung (negatif ala akuntansi) dan notasi ilmiah.
     * Sel bertipe angka asli tidak diperiksa (nilainya sudah pasti).
     */
    private function unsupportedAmount($v): ?string
    {
        if (! is_string($v)) {
            return null;
        }
        $t = trim($v);
        if (preg_match('/\(.*\d.*\)/', $t) === 1 || preg_match('/^[^a-z]*\d[.,]?\d*\s*e\s*[+\-]?\d+\s*$/i', $t) === 1) {
            return mb_substr($t, 0, 30);
        }

        return null;
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
    private function rawDate($cell, bool &$formulaMissing = false): array
    {
        if ($cell === null) {
            return ['kind' => 'none'];
        }
        $v = $this->cellValue($cell, $formulaMissing);

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

        [$scoreSwap, $scoreAsis] = $this->orderScores($raw);

        return $scoreSwap < $scoreAsis;
    }

    /**
     * Skor urutan tanggal untuk tafsir "tukar" dan "apa adanya": jumlah pelanggaran urutan terkecil
     * antara urut naik dan urut turun (file kas boleh terurut menurun). Makin kecil makin kronologis.
     *
     * @return array{0:int,1:int}
     */
    private function orderScores(array $raw): array
    {
        $score = function (bool $swap) use ($raw): int {
            $prev = null;
            $asc = 0;
            $desc = 0;
            foreach ($raw as $r) {
                $d = $this->resolveDate($r['date'], $swap);
                if (! $d) {
                    continue;
                }
                if ($prev !== null) {
                    if ($d < $prev) {
                        $asc++;
                    } elseif ($d > $prev) {
                        $desc++;
                    }
                }
                $prev = $d;
            }

            return min($asc, $desc);
        };

        return [$score(true), $score(false)];
    }

    /**
     * Peringatan untuk mode auto bila tafsir tanggal tidak bisa dipastikan dari urutan baris.
     * Hanya relevan bila ada sel tanggal yang hasilnya berbeda antara dua tafsir.
     */
    private function dateAmbiguityWarning(array $raw): ?string
    {
        $affected = 0;
        foreach ($raw as $r) {
            $d = $r['date'];
            if ($d['kind'] === 'cell' && $d['d'] !== $d['m'] && $this->resolveDate($d, true) !== $this->resolveDate($d, false)) {
                $affected++;
            }
        }
        if ($affected === 0) {
            return null;
        }

        [$scoreSwap, $scoreAsis] = $this->orderScores($raw);

        if ($scoreSwap === $scoreAsis) {
            return "Tafsir hari/bulan tidak dapat ditentukan otomatis ({$affected} baris tanggal-Excel bisa dibaca dua cara dan urutan barisnya tidak membedakan). "
                . 'Dibaca apa adanya. Periksa kolom Tanggal, atau ulangi dengan mode "Tukar hari dan bulan" atau "Apa adanya" sesuai file Anda.';
        }

        if (min($scoreSwap, $scoreAsis) > 0) {
            return "Tafsir hari/bulan belum pasti: urutan tanggal di file tidak konsisten walau sudah ditafsirkan ({$affected} baris tanggal-Excel terpengaruh). "
                . 'Periksa kolom Tanggal di pratinjau, atau pilih mode "Tukar hari dan bulan" atau "Apa adanya" secara manual.';
        }

        return null;
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

        if ($date && ((int) substr($date, 0, 4) < self::MIN_YEAR || (int) substr($date, 0, 4) > self::MAX_YEAR)) {
            $error = 'Tahun ' . substr($date, 0, 4) . ' di luar rentang yang wajar (' . self::MIN_YEAR . '-' . self::MAX_YEAR . ')';
        }
        if (! $error && ! empty($r['amount_unsupported'])) {
            $error = 'Format angka tidak didukung: "' . $r['amount_unsupported'] . '". Tulis angka biasa tanpa tanda kurung atau notasi ilmiah.';
        }
        if (! $error && mb_strlen($r['number']) > self::MAX_NUMBER_LENGTH) {
            $error = 'No. Transaksi terlalu panjang (maksimal ' . self::MAX_NUMBER_LENGTH . ' karakter)';
        }
        if (! $error && mb_strlen($r['notes']) > self::MAX_NOTES_LENGTH) {
            $error = 'Catatan terlalu panjang (maksimal ' . self::MAX_NOTES_LENGTH . ' karakter)';
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

        if (($r['formula_missing'] ?? false) && $error !== null) {
            $error = 'Sel berisi formula tanpa nilai tersimpan. Buka file di Excel lalu simpan ulang agar nilainya ikut tersimpan.';
        }

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

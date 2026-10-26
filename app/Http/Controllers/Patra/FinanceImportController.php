<?php

namespace App\Http\Controllers\Patra;

use App\Exceptions\UserFacingException;
use App\Http\Controllers\Controller;
use App\Models\FinanceCategory;
use App\Models\FinancePeriod;
use App\Models\FinanceTransaction;
use App\Services\FinanceImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FinanceImportController extends Controller
{
    private const SESSION_KEY = 'finance_import';

    protected $route = 'patra.finance.';
    protected $view = 'patra.pages.finance.';

    public function __construct(private FinanceImportService $service)
    {
    }

    public function create()
    {
        return view($this->view . 'import');
    }

    /** Langkah 1: baca file, tampilkan pratinjau (belum ada yang disimpan). */
    public function preview(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:5120', 'mimes:xlsx,xls,csv,txt'],
            'date_mode' => ['required', 'in:auto,swap,asis'],
        ], [
            'file.required' => 'File Excel harus dipilih',
            'file.mimes' => 'Format file harus .xlsx, .xls, atau .csv',
            'file.max' => 'Ukuran file maksimal 5 MB',
        ]);

        try {
            $parsed = $this->service->parse($request->file('file')->getRealPath(), $request->date_mode);
            $rows = $this->service->annotate($parsed['rows']);
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            alert()->error('Gagal', \App\Helpers\ErrorHelper::userMessage($e));

            return redirect()->route($this->route . 'import.create');
        }

        $token = (string) Str::uuid();
        $request->session()->put(self::SESSION_KEY, ['token' => $token, 'rows' => $rows, 'user' => Auth::id()]);

        return view($this->view . 'import_preview', [
            'token' => $token,
            'rows' => $rows,
            'sheet' => $parsed['sheet'],
            'warnings' => $parsed['warnings'],
            'summary' => $this->service->summarize($rows),
            'categories' => FinanceCategory::with('children')->whereNull('parent_id')->where('is_active', true)->orderBy('type')->orderBy('urutan')->get(),
        ]);
    }

    /** Langkah 2: simpan baris berstatus "ok" (kategori boleh diubah di pratinjau). */
    public function store(Request $request)
    {
        $data = $request->session()->get(self::SESSION_KEY);

        if (! $data || ($data['token'] ?? null) !== $request->token || ($data['user'] ?? null) !== Auth::id()) {
            alert()->error('Gagal', 'Sesi pratinjau habis. Unggah ulang file Excel.');

            return redirect()->route($this->route . 'import.create');
        }

        $override = (array) $request->input('category', []);
        $cats = FinanceCategory::where('is_active', true)->get()->keyBy('id');

        try {
            $imported = 0;
            $skipped = 0;

            DB::transaction(function () use ($data, $override, $cats, &$imported, &$skipped) {
                foreach ($data['rows'] as $i => $row) {
                    if ($row['status'] !== 'ok') {
                        continue;
                    }

                    $catId = (int) ($override[$i] ?? $row['category_id']);
                    $cat = $cats->get($catId);
                    if (! $cat || $cat->type !== $row['type']) {
                        throw new UserFacingException("Baris Excel {$row['excel_row']}: kategori belum dipilih atau tidak sesuai jenis transaksi.");
                    }

                    // Cek ulang saat simpan (data bisa berubah sejak pratinjau).
                    if (FinancePeriod::isClosedFor($row['date'])
                        || ($row['number'] !== '' && FinanceTransaction::withTrashed()->where('transaction_number', $row['number'])->exists())) {
                        $skipped++;
                        continue;
                    }

                    FinanceTransaction::create([
                        'transaction_number' => $row['number'] !== '' ? $row['number'] : FinanceTransaction::generateTransactionNumber(),
                        'date' => $row['date'],
                        'description' => $row['description'],
                        'finance_category_id' => $cat->id,
                        'type' => $row['type'],
                        'amount' => $row['amount'],
                        'payment_method' => $row['payment_method'],
                        'notes' => $row['notes'],
                        'created_by' => Auth::id(),
                    ]);
                    $imported++;
                }
            });

            $request->session()->forget(self::SESSION_KEY);
            Log::info('Impor Excel buku kas', ['imported' => $imported, 'skipped' => $skipped, 'by' => Auth::id()]);

            alert()->html('Berhasil', "{$imported} transaksi diimpor" . ($skipped ? ", {$skipped} dilewati" : '') . '.', 'success');

            return redirect()->route($this->route . 'index');
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            alert()->error('Gagal', \App\Helpers\ErrorHelper::userMessage($e));

            return redirect()->route($this->route . 'import.create');
        }
    }
}

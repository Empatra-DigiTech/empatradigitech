<?php

namespace App\Http\Controllers\Patra;

use App\Http\Controllers\Controller;
use App\Models\FinanceCategory;
use App\Models\FinanceTransaction;
use App\Models\FinancePeriod;
use App\Exceptions\UserFacingException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\FinanceTransaction\StoreRequest;
use App\Http\Requests\FinanceTransaction\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;

class FinanceTransactionController extends Controller
{
    protected $transaction;
    protected $route;
    protected $view;

    const PAYMENT_METHODS = ['Transfer', 'Tunai', 'QRIS', 'Lainnya'];

    public function __construct()
    {
        $this->route = "patra.finance.";
        $this->view = "patra.pages.finance.";
        $this->transaction = new FinanceTransaction();
        Paginator::useBootstrap();
    }

    /** Transaksi pada periode yang sudah ditutup tidak boleh dibuat/diubah/dihapus/dipulihkan. */
    private function assertPeriodOpen($date): void
    {
        if (FinancePeriod::isClosedFor($date)) {
            throw new UserFacingException(
                'Periode ' . Carbon::parse($date)->format('Y-m') . ' sudah ditutup. Buka kembali periode di menu Periode untuk mengubah transaksi.'
            );
        }
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $type = $request->type;
        $categoryId = $request->kategori;
        $bulan = $request->bulan; // format: YYYY-MM
        $sort = $request->sort === 'asc' ? 'asc' : 'desc';

        $filtered = FinanceTransaction::query()
            ->when(!empty($search), fn ($q) => $q->where('description', 'like', '%' . addcslashes($search, '%_\\') . '%'))
            ->when(in_array($type, ['masuk', 'keluar'], true), fn ($q) => $q->where('type', $type))
            ->when(!empty($categoryId), fn ($q) => $q->where('finance_category_id', (int) $categoryId))
            ->when(!empty($bulan) && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $bulan), function ($q) use ($bulan) {
                $start = Carbon::createFromFormat('Y-m-d', $bulan . '-01')->startOfMonth();
                $q->whereDate('date', '>=', $start->toDateString())
                  ->whereDate('date', '<=', $start->copy()->endOfMonth()->toDateString());
            });

        // Total hasil filter: agregasi di database (tidak memuat semua baris ke memori).
        $totals = (clone $filtered)->toBase()
            ->selectRaw("COALESCE(SUM(CASE WHEN type = 'masuk' THEN amount END), 0) AS masuk")
            ->selectRaw("COALESCE(SUM(CASE WHEN type = 'keluar' THEN amount END), 0) AS keluar")
            ->first();

        // Saldo berjalan tiap baris = jumlah bersih SEMUA transaksi (tanpa filter)
        // yang urutannya <= baris itu menurut (tanggal, id). Dihitung lewat subquery
        // hanya untuk baris di halaman yang tampil, sehingga tetap sama dengan
        // kolom "Saldo Akhir" di pembukuan Excel walau daftar difilter.
        $running = DB::table('finance_transactions as t2')
            ->selectRaw("COALESCE(SUM(CASE WHEN t2.type = 'masuk' THEN t2.amount ELSE -t2.amount END), 0)")
            ->whereNull('t2.deleted_at')
            ->where(function ($q) {
                $q->whereColumn('t2.date', '<', 'finance_transactions.date')
                  ->orWhere(function ($q) {
                      $q->whereColumn('t2.date', 'finance_transactions.date')
                        ->whereColumn('t2.id', '<=', 'finance_transactions.id');
                  });
            });

        $table = (clone $filtered)
            ->with('category')
            ->select('finance_transactions.*')
            ->selectSub($running, 'saldo_berjalan')
            ->orderBy('date', $sort)
            ->orderBy('id', $sort)
            ->paginate(20)
            ->withQueryString();

        $saldoSaatIni = (float) FinanceTransaction::query()->toBase()
            ->selectRaw("COALESCE(SUM(CASE WHEN type = 'masuk' THEN amount ELSE -amount END), 0) AS saldo")
            ->value('saldo');

        $categories = FinanceCategory::with('children')->whereNull('parent_id')->orderBy('type')->orderBy('urutan')->get();

        $data = [
            'table' => $table,
            'categories' => $categories,
            'totalMasuk' => $totals->masuk,
            'totalKeluar' => $totals->keluar,
            'saldoSaatIni' => $saldoSaatIni,
            'closedPeriods' => FinancePeriod::closedPeriods(),
            'trashedCount' => FinanceTransaction::onlyTrashed()->count(),
            'filterSearch' => $search,
            'filterType' => $type,
            'filterCategory' => $categoryId,
            'filterBulan' => $bulan,
            'filterSort' => $sort,
        ];

        return view($this->view . "index", $data);
    }

    public function create()
    {
        $categories = FinanceCategory::with('children')->whereNull('parent_id')->where('is_active', true)->orderBy('type')->orderBy('urutan')->get();
        $transactionNumber = FinanceTransaction::generateTransactionNumber();

        $data = [
            'categories' => $categories,
            'transactionNumber' => $transactionNumber,
            'paymentMethods' => self::PAYMENT_METHODS,
        ];

        return view($this->view . "create", $data);
    }

    public function store(StoreRequest $request)
    {
        try {
            $this->assertPeriodOpen($request->date);

            $this->transaction->create([
                'transaction_number' => FinanceTransaction::generateTransactionNumber(),
                'date' => $request->date,
                'description' => $request->description,
                'finance_category_id' => $request->finance_category_id,
                'type' => $request->type,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
                'created_by' => Auth::id(),
            ]);

            alert()->html('Berhasil', 'Transaksi berhasil dicatat', 'success');
            return redirect()->route($this->route . "index");
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            alert()->error('Gagal', \App\Helpers\ErrorHelper::userMessage($e));
            return redirect()->route($this->route . "create")->withInput();
        }
    }

    public function edit($id)
    {
        $result = $this->transaction->find($id);

        if (!$result) {
            alert()->error('Gagal', "Transaksi tidak ditemukan");
            return redirect()->route($this->route . "index");
        }

        $categories = FinanceCategory::with('children')->whereNull('parent_id')->orderBy('type')->orderBy('urutan')->get();

        $data = [
            'result' => $result,
            'categories' => $categories,
            'paymentMethods' => self::PAYMENT_METHODS,
        ];

        return view($this->view . "edit", $data);
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $result = $this->transaction->find($id);

            if (!$result) {
                throw new \App\Exceptions\UserFacingException("Transaksi tidak ditemukan");
            }

            // Periode lama DAN periode tujuan (bila tanggal dipindah) harus terbuka.
            $this->assertPeriodOpen($result->date);
            $this->assertPeriodOpen($request->date);

            $result->update([
                'date' => $request->date,
                'description' => $request->description,
                'finance_category_id' => $request->finance_category_id,
                'type' => $request->type,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            alert()->html('Berhasil', 'Transaksi berhasil diubah', 'success');
            return redirect()->route($this->route . "index");
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            alert()->error('Gagal', \App\Helpers\ErrorHelper::userMessage($e));
            return redirect()->route($this->route . "edit", $id)->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $result = $this->transaction->find($id);

            if (!$result) {
                throw new \App\Exceptions\UserFacingException("Transaksi tidak ditemukan");
            }

            $this->assertPeriodOpen($result->date);

            // Siapa yang menghapus dicatat; saveQuietly agar tidak ada log "update" ganda.
            $result->forceFill(['deleted_by' => Auth::id()])->saveQuietly();
            $result->delete(); // soft delete, dicatat oleh Loggable

            Log::info('Transaksi keuangan dihapus (soft delete)', ['id' => $result->id, 'number' => $result->transaction_number, 'by' => Auth::id()]);

            alert()->html('Berhasil', 'Transaksi dipindahkan ke Sampah dan bisa dipulihkan', 'success');
            return redirect()->route($this->route . "index");
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            alert()->error('Gagal', \App\Helpers\ErrorHelper::userMessage($e));
            return redirect()->route($this->route . "index");
        }
    }

    public function trash()
    {
        $table = FinanceTransaction::onlyTrashed()
            ->with(['category', 'creator'])
            ->orderByDesc('deleted_at')
            ->paginate(20);

        return view($this->view . "trash", [
            'table' => $table,
            'closedPeriods' => FinancePeriod::closedPeriods(),
            'deleters' => \App\Models\User::whereIn('id', $table->pluck('deleted_by')->filter()->unique())->pluck('name', 'id'),
        ]);
    }

    public function restore($id)
    {
        try {
            $result = $this->transaction->onlyTrashed()->find($id);

            if (!$result) {
                throw new UserFacingException("Transaksi di Sampah tidak ditemukan");
            }

            $this->assertPeriodOpen($result->date);

            $result->restore();
            $result->forceFill(['deleted_by' => null])->saveQuietly();

            Log::info('Transaksi keuangan dipulihkan', ['id' => $result->id, 'number' => $result->transaction_number, 'by' => Auth::id()]);

            alert()->html('Berhasil', 'Transaksi berhasil dipulihkan', 'success');
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            alert()->error('Gagal', \App\Helpers\ErrorHelper::userMessage($e));
        }

        return redirect()->route($this->route . "trash");
    }
}

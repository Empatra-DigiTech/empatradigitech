<?php

namespace App\Http\Controllers\Patra;

use App\Http\Controllers\Controller;
use App\Models\FinanceCategory;
use App\Models\FinanceTransaction;
use App\Http\Requests\FinanceTransaction\StoreRequest;
use App\Http\Requests\FinanceTransaction\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
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

    public function index(Request $request)
    {
        // Saldo berjalan harus dihitung dari SELURUH riwayat transaksi secara
        // kronologis (bukan dari hasil yang sudah difilter/dipaginasi), persis
        // seperti kolom "Saldo Akhir" di pembukuan Excel sebelumnya. Jadi semua
        // data diambil dulu, baru difilter & dipaginasi di PHP.
        $all = $this->transaction->with('category')
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        $saldoSaatIni = 0;
        $all = $all->map(function ($row) use (&$saldoSaatIni) {
            $saldoSaatIni += $row->type === 'masuk' ? (float) $row->amount : -(float) $row->amount;
            $row->saldo_berjalan = $saldoSaatIni;
            return $row;
        });

        $search = $request->search;
        $type = $request->type;
        $categoryId = $request->kategori;
        $bulan = $request->bulan; // format: YYYY-MM
        $sort = $request->sort === 'asc' ? 'asc' : 'desc';

        $filtered = $all->filter(function ($row) use ($search, $type, $categoryId, $bulan) {
            if (!empty($search) && stripos($row->description, $search) === false) {
                return false;
            }
            if (!empty($type) && $row->type !== $type) {
                return false;
            }
            if (!empty($categoryId) && (int) $row->finance_category_id !== (int) $categoryId) {
                return false;
            }
            if (!empty($bulan) && $row->date->format('Y-m') !== $bulan) {
                return false;
            }
            return true;
        })->values();

        $totalMasuk = $filtered->where('type', 'masuk')->sum('amount');
        $totalKeluar = $filtered->where('type', 'keluar')->sum('amount');

        $ordered = $sort === 'asc' ? $filtered : $filtered->reverse()->values();

        $perPage = 20;
        $page = Paginator::resolveCurrentPage('page') ?: 1;
        $table = new LengthAwarePaginator(
            $ordered->forPage($page, $perPage),
            $ordered->count(),
            $perPage,
            $page,
            ['path' => Paginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        $categories = FinanceCategory::with('children')->whereNull('parent_id')->orderBy('type')->orderBy('urutan')->get();

        $data = [
            'table' => $table,
            'categories' => $categories,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'saldoSaatIni' => $saldoSaatIni,
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

            $result->delete();

            alert()->html('Berhasil', 'Transaksi berhasil dihapus', 'success');
            return redirect()->route($this->route . "index");
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            alert()->error('Gagal', \App\Helpers\ErrorHelper::userMessage($e));
            return redirect()->route($this->route . "index");
        }
    }
}

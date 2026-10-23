<?php

namespace App\Http\Controllers\Patra;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Http\Requests\Invoice\StoreRequest;
use App\Http\Requests\Invoice\UpdateRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    protected $invoice;
    protected $route;
    protected $view;

    public function __construct()
    {
        $this->route = "patra.invoice.";
        $this->view = "patra.pages.invoice.";
        $this->invoice = new Invoice();
        Paginator::useBootstrap();
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $table = $this->invoice;

        if (!empty($search)) {
            $table = $table->where(function ($query) use ($search) {
                $query->where("invoice_number", "like", "%" . $search . "%")
                    ->orWhere("bill_to_name", "like", "%" . $search . "%");
            });
        }

        $table = $table->orderBy("created_at", "DESC");
        $table = $table->paginate(10)->withQueryString();

        $data = [
            'table' => $table,
        ];

        return view($this->view . "index", $data);
    }

    public function create()
    {
        $invoiceNumber = Invoice::generateInvoiceNumber();

        $data = [
            'invoiceNumber' => $invoiceNumber,
        ];

        return view($this->view . "create", $data);
    }

    public function store(StoreRequest $request)
    {
        try {
            $data = $request->invoiceData();

            $invoice = $this->invoice->create($data);
            $invoice->calculateTotals();
            $invoice->save();

            alert()->html('Berhasil', 'Invoice berhasil dibuat', 'success');
            return redirect()->route($this->route . "index");
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            alert()->error('Gagal', \App\Helpers\ErrorHelper::userMessage($e));
            return redirect()->route($this->route . "create")->withInput();
        }
    }

    public function show($id)
    {
        $result = $this->invoice->find($id);

        if (!$result) {
            alert()->error('Gagal', "Invoice tidak ditemukan");
            return redirect()->route($this->route . "index");
        }

        $data = [
            'result' => $result,
        ];

        return view($this->view . "show", $data);
    }

    public function edit($id)
    {
        $invoice = $this->invoice->find($id); // Ubah dari $result ke $invoice

        if (!$invoice) {
            alert()->error('Gagal', "Invoice tidak ditemukan");
            return redirect()->route($this->route . "index");
        }

        $data = [
            'invoice' => $invoice,
        ];

        return view($this->view . "edit", $data);
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $invoice = $this->invoice->find($id); // Ubah dari $result ke $invoice

            if (!$invoice) {
                throw new \App\Exceptions\UserFacingException("Invoice tidak ditemukan");
            }

            $data = $request->invoiceData();

            $invoice->update($data);
            $invoice->calculateTotals();
            $invoice->save();

            alert()->html('Berhasil', 'Invoice berhasil diupdate', 'success');
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
            $result = $this->invoice->find($id);

            if (!$result) {
                throw new \App\Exceptions\UserFacingException("Invoice tidak ditemukan");
            }

            $result->delete();

            alert()->html('Berhasil', 'Invoice berhasil dihapus', 'success');
            return redirect()->route($this->route . "index");
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            alert()->error('Gagal', \App\Helpers\ErrorHelper::userMessage($e));
            return redirect()->route($this->route . "index");
        }
    }

    public function download($id)
    {
        $invoice = $this->invoice->find($id);

        if (!$invoice) {
            alert()->error('Gagal', "Invoice tidak ditemukan");
            return redirect()->route($this->route . "index");
        }

        $pdf = PDF::loadView($this->view . 'pdf', ['invoice' => $invoice]);

        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }
}

<?php

namespace App\Http\Controllers\Patra;

use App\Http\Controllers\Controller;
use App\Models\FinanceCategory;
use App\Http\Requests\FinanceCategory\StoreRequest;
use App\Http\Requests\FinanceCategory\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;

class FinanceCategoryController extends Controller
{
    protected $category;
    protected $route;
    protected $view;

    public function __construct()
    {
        $this->route = "patra.finance-category.";
        $this->view = "patra.pages.finance_category.";
        $this->category = new FinanceCategory();
        Paginator::useBootstrap();
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $table = $this->category->with(['parent', 'children']);

        if (!empty($search)) {
            $table = $table->where("name", "like", "%" . $search . "%");
        }

        $table = $table->orderBy("type")->orderBy("parent_id")->orderBy("urutan");
        $table = $table->paginate(20)->withQueryString();

        $data = [
            'table' => $table,
        ];

        return view($this->view . "index", $data);
    }

    public function create()
    {
        $parents = $this->category->whereNull('parent_id')->orderBy('type')->orderBy('urutan')->get();

        $data = [
            'parents' => $parents,
        ];

        return view($this->view . "create", $data);
    }

    public function store(StoreRequest $request)
    {
        try {
            $this->category->create([
                'name' => $request->name,
                'type' => $request->type,
                'parent_id' => $request->parent_id ?: null,
                'urutan' => $request->urutan ?? 0,
                'is_active' => $request->has('is_active'),
            ]);

            alert()->html('Berhasil', 'Kategori berhasil ditambahkan', 'success');
            return redirect()->route($this->route . "index");
        } catch (\Throwable $e) {
            Log::emergency($e->getMessage());
            alert()->error('Gagal', $e->getMessage());
            return redirect()->route($this->route . "create")->withInput();
        }
    }

    public function edit($id)
    {
        $result = $this->category->find($id);

        if (!$result) {
            alert()->error('Gagal', "Kategori tidak ditemukan");
            return redirect()->route($this->route . "index");
        }

        $parents = $this->category->whereNull('parent_id')->where('id', '!=', $id)->orderBy('type')->orderBy('urutan')->get();

        $data = [
            'result' => $result,
            'parents' => $parents,
        ];

        return view($this->view . "edit", $data);
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $result = $this->category->find($id);

            if (!$result) {
                throw new \Exception("Kategori tidak ditemukan");
            }

            // Kategori tidak boleh jadi induk untuk dirinya sendiri.
            $parentId = $request->parent_id ?: null;
            if ($parentId == $id) {
                $parentId = null;
            }

            $result->update([
                'name' => $request->name,
                'type' => $request->type,
                'parent_id' => $parentId,
                'urutan' => $request->urutan ?? 0,
                'is_active' => $request->has('is_active'),
            ]);

            alert()->html('Berhasil', 'Kategori berhasil diubah', 'success');
            return redirect()->route($this->route . "index");
        } catch (\Throwable $e) {
            Log::emergency($e->getMessage());
            alert()->error('Gagal', $e->getMessage());
            return redirect()->route($this->route . "edit", $id)->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $result = $this->category->find($id);

            if (!$result) {
                throw new \Exception("Kategori tidak ditemukan");
            }

            if ($result->transactions()->exists()) {
                throw new \Exception("Kategori tidak bisa dihapus karena masih dipakai di transaksi. Nonaktifkan saja kategorinya.");
            }

            if ($result->children()->exists()) {
                throw new \Exception("Kategori tidak bisa dihapus karena masih punya sub kategori. Hapus/pindahkan sub kategorinya dulu.");
            }

            $result->delete();

            alert()->html('Berhasil', 'Kategori berhasil dihapus', 'success');
            return redirect()->route($this->route . "index");
        } catch (\Throwable $e) {
            Log::emergency($e->getMessage());
            alert()->error('Gagal', $e->getMessage());
            return redirect()->route($this->route . "index");
        }
    }
}

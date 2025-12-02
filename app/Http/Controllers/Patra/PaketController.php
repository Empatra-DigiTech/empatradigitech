<?php

namespace App\Http\Controllers\Patra;

use App\Http\Controllers\Controller;
use App\Models\Paket;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Paket\StoreRequest;
use App\Http\Requests\Paket\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class PaketController extends Controller
{
    protected $paket;
    protected $route;
    protected $view;

    public function __construct()
    {
        $this->route = "patra.paket.";
        $this->view = "patra.pages.paket.";
        $this->paket = new Paket();
        Paginator::useBootstrap();
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $tipe = $request->tipe;

        $table = $this->paket;

        if (!empty($search)) {
            $table = $table->where(function ($query) use ($search) {
                $query->where("nama_paket", "like", "%" . $search . "%")
                      ->orWhere("tagline", "like", "%" . $search . "%");
            });
        }

        if (!empty($tipe)) {
            $table = $table->where('tipe', $tipe);
        }

        $table = $table->orderBy("urutan", "ASC")
                       ->orderBy("created_at", "DESC");
        $table = $table->paginate(10)->withQueryString();

        $data = [
            'table' => $table,
        ];

        return view($this->view . "index", $data);
    }

    public function create()
    {
        return view($this->view . "create");
    }

    public function store(StoreRequest $request)
    {
        try {
            $data = $request->validated();

            // Set default values
            $data['is_recommended'] = $request->has('is_recommended') ? 1 : 0;
            $data['is_active'] = $request->has('is_active') ? 1 : 0;

            // Handle fitur array
            if ($request->has('fitur')) {
                $data['fitur'] = array_filter($request->fitur); // Remove empty entries
            }

            $create = $this->paket->create($data);

            alert()->html('Berhasil', 'Data paket berhasil ditambahkan', 'success');
            return redirect()->route($this->route . "index");

        } catch (\Throwable $e) {
            Log::emergency($e->getMessage());
            alert()->error('Gagal', $e->getMessage());
            return redirect()->route($this->route . "create")->withInput();
        }
    }

    public function show($id)
    {
        $result = $this->paket->find($id);

        if (!$result) {
            alert()->error('Gagal', "Data tidak ditemukan");
            return redirect()->route($this->route . "index");
        }

        $data = [
            'result' => $result,
        ];

        return view($this->view . "show", $data);
    }

    public function edit($id)
    {
        $result = $this->paket->find($id);

        if (!$result) {
            alert()->error('Gagal', "Data tidak ditemukan");
            return redirect()->route($this->route . "index");
        }

        $data = [
            'result' => $result,
        ];

        return view($this->view . "edit", $data);
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $result = $this->paket->find($id);

            if (!$result) {
                throw new \Exception("Data tidak ditemukan");
            }

            $data = $request->validated();

            // Set default values
            $data['is_recommended'] = $request->has('is_recommended') ? 1 : 0;
            $data['is_active'] = $request->has('is_active') ? 1 : 0;

            // Handle fitur array
            if ($request->has('fitur')) {
                $data['fitur'] = array_filter($request->fitur);
            }

            $result->update($data);

            alert()->html('Berhasil', 'Data paket berhasil diubah', 'success');
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
            $result = $this->paket->find($id);

            if (!$result) {
                throw new \Exception("Data tidak ditemukan");
            }

            $result->delete();

            alert()->html('Berhasil', 'Data paket berhasil dihapus', 'success');
            return redirect()->route($this->route . "index");

        } catch (\Throwable $e) {
            Log::emergency($e->getMessage());
            alert()->error('Gagal', $e->getMessage());
            return redirect()->route($this->route . "index");
        }
    }
}

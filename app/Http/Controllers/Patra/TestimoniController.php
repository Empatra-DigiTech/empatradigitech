<?php

namespace App\Http\Controllers\Patra;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Testimoni\StoreRequest;
use App\Http\Requests\Testimoni\UpdateRequest;
use App\Helpers\UploadHelper;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Error;

class TestimoniController extends Controller
{
    protected $testimoni;

    public function __construct()
    {
        $this->route = "patra.testimoni.";
        $this->view = "patra.pages.testimoni.";
        $this->testimoni = new Testimoni();
        Paginator::useBootstrap();
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $table = $this->testimoni;

        if (!empty($search)) {
            $table = $table->where(function ($query2) use ($search) {
                $query2->where("nama_client", "like", "%" . $search . "%")
                    ->orWhere("perusahaan", "like", "%" . $search . "%");
            });
        }

        $table = $table->orderBy("urutan", "ASC")->orderBy("created_at", "DESC");
        $table = $table->paginate(15)->withQueryString();

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
            $foto = $request->file("foto");

            if ($foto) {
                $upload = UploadHelper::upload_file($foto, 'testimoni', ['jpeg', 'jpg', 'png', 'gif']);

                if ($upload["IsError"] == TRUE) {
                    throw new Error($upload["Message"]);
                }

                $foto = $upload["Path"];
            } else {
                $foto = null;
            }

            $this->testimoni->create([
                'nama_client' => $request->nama_client,
                'jabatan' => $request->jabatan,
                'perusahaan' => $request->perusahaan,
                'rating' => $request->rating,
                'testimoni' => $request->testimoni,
                'foto' => $foto,
                'urutan' => $request->urutan ?? 0,
                'is_active' => $request->has('is_active'),
            ]);

            alert()->html('Berhasil', 'Data berhasil ditambahkan', 'success');
            return redirect()->route($this->route . "index");
        } catch (\Throwable $e) {
            Log::emergency($e->getMessage());
            alert()->error('Gagal', $e->getMessage());
            return redirect()->route($this->route . "create")->withInput();
        }
    }

    public function show($id)
    {
        $result = $this->testimoni->where('id', $id)->first();

        if (!$result) {
            alert()->error('Gagal', "Data tidak ditemukan");
            return redirect()->route($this->route . "index");
        }

        return view($this->view . "show", ['result' => $result]);
    }

    public function edit($id)
    {
        $result = $this->testimoni->where('id', $id)->first();

        if (!$result) {
            alert()->error('Gagal', "Data tidak ditemukan");
            return redirect()->route($this->route . "index");
        }

        return view($this->view . "edit", ['result' => $result]);
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $result = $this->testimoni->where('id', $id)->first();

            if (!$result) {
                throw new \Error("Data tidak ditemukan");
            }

            $foto = $request->file("foto");

            if ($foto) {
                $upload = UploadHelper::upload_file($foto, 'testimoni', ['jpeg', 'jpg', 'png', 'gif']);

                if ($upload["IsError"] == TRUE) {
                    throw new Error($upload["Message"]);
                }

                $foto = $upload["Path"];
            } else {
                $foto = $result->foto;
            }

            $result->update([
                'nama_client' => $request->nama_client,
                'jabatan' => $request->jabatan,
                'perusahaan' => $request->perusahaan,
                'rating' => $request->rating,
                'testimoni' => $request->testimoni,
                'foto' => $foto,
                'urutan' => $request->urutan ?? 0,
                'is_active' => $request->has('is_active'),
            ]);

            alert()->html('Berhasil', 'Data berhasil diubah', 'success');
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
            $result = $this->testimoni->where('id', $id)->first();
            $result->delete();

            alert()->html('Berhasil', 'Data berhasil dihapus', 'success');
            return redirect()->route($this->route . "index");
        } catch (\Throwable $e) {
            Log::emergency($e->getMessage());
            alert()->error('Gagal', $e->getMessage());
            return redirect()->route($this->route . "index");
        }
    }
}

<?php

namespace App\Http\Controllers\Patra;

use App\Http\Controllers\Controller;
use App\Models\ClientLogo;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ClientLogo\StoreRequest;
use App\Http\Requests\ClientLogo\UpdateRequest;
use App\Helpers\UploadHelper;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Error;

class ClientLogoController extends Controller
{
    protected $clientLogo;

    public function __construct()
    {
        $this->route = "patra.client-logo.";
        $this->view = "patra.pages.client_logo.";
        $this->clientLogo = new ClientLogo();
        Paginator::useBootstrap();
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $table = $this->clientLogo;

        if (!empty($search)) {
            $table = $table->where("nama_client", "like", "%" . $search . "%");
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
            $logo = $request->file("logo");
            $upload = UploadHelper::upload_file($logo, 'client-logo', ['jpeg', 'jpg', 'png', 'gif', 'svg']);

            if ($upload["IsError"] == TRUE) {
                throw new Error($upload["Message"]);
            }

            $this->clientLogo->create([
                'nama_client' => $request->nama_client,
                'website_url' => $request->website_url,
                'logo' => $upload["Path"],
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
        $result = $this->clientLogo->where('id', $id)->first();

        if (!$result) {
            alert()->error('Gagal', "Data tidak ditemukan");
            return redirect()->route($this->route . "index");
        }

        return view($this->view . "show", ['result' => $result]);
    }

    public function edit($id)
    {
        $result = $this->clientLogo->where('id', $id)->first();

        if (!$result) {
            alert()->error('Gagal', "Data tidak ditemukan");
            return redirect()->route($this->route . "index");
        }

        return view($this->view . "edit", ['result' => $result]);
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $result = $this->clientLogo->where('id', $id)->first();

            if (!$result) {
                throw new \Error("Data tidak ditemukan");
            }

            $logo = $request->file("logo");

            if ($logo) {
                $upload = UploadHelper::upload_file($logo, 'client-logo', ['jpeg', 'jpg', 'png', 'gif', 'svg']);

                if ($upload["IsError"] == TRUE) {
                    throw new Error($upload["Message"]);
                }

                $logo = $upload["Path"];
            } else {
                $logo = $result->logo;
            }

            $result->update([
                'nama_client' => $request->nama_client,
                'website_url' => $request->website_url,
                'logo' => $logo,
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
            $result = $this->clientLogo->where('id', $id)->first();
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

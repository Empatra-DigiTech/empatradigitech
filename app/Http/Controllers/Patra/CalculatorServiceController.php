<?php

namespace App\Http\Controllers\Patra;
use App\Http\Controllers\Controller;
use App\Models\CalculatorService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class CalculatorServiceController extends Controller
{
    protected $service;
    protected $route = 'patra.calculator-service.';

    public function __construct(){
        $this->route = "patra.calculator-service.";
        $this->view = "patra.pages.calculator-service.";
        $this->service = new CalculatorService();
        Paginator::useBootstrap();
    }

    public function index(Request $request)
    {
        $table = $this->service->orderBy("urutan","ASC")->orderBy("created_at","DESC")->paginate(15);
        return view($this->view."index",['table' => $table]);
    }

    public function create()
    {
        return view($this->view."create");
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_layanan' => 'required',
                'harga_dasar' => 'required|numeric|min:0',
                'harga_per_halaman' => 'required|numeric|min:0',
            ]);

            $this->service->create([
                'nama_layanan' => $request->nama_layanan,
                'harga_dasar' => $request->harga_dasar,
                'harga_per_halaman' => $request->harga_per_halaman,
                'deskripsi' => $request->deskripsi,
                'urutan' => $request->urutan ?? 0,
                'is_active' => $request->has('is_active'),
            ]);

            alert()->html('Berhasil','Data berhasil ditambahkan','success');
            return redirect()->route($this->route."index");

        } catch (\Throwable $e) {
            Log::emergency($e->getMessage());
            alert()->error('Gagal',$e->getMessage());
            return redirect()->route($this->route."create")->withInput();
        }
    }

    public function edit($id)
    {
        $result = $this->service->where('id',$id)->first();
        if(!$result){
            alert()->error('Gagal',"Data tidak ditemukan");
            return redirect()->route($this->route."index");
        }
        return view($this->view."edit",['result' => $result]);
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nama_layanan' => 'required',
                'harga_dasar' => 'required|numeric|min:0',
                'harga_per_halaman' => 'required|numeric|min:0',
            ]);

            $result = $this->service->where('id',$id)->first();
            if(!$result){
                throw new \Error("Data tidak ditemukan");
            }

            $result->update([
                'nama_layanan' => $request->nama_layanan,
                'harga_dasar' => $request->harga_dasar,
                'harga_per_halaman' => $request->harga_per_halaman,
                'deskripsi' => $request->deskripsi,
                'urutan' => $request->urutan ?? 0,
                'is_active' => $request->has('is_active'),
            ]);

            alert()->html('Berhasil','Data berhasil diubah','success');
            return redirect()->route($this->route."index");

        } catch (\Throwable $e) {
            Log::emergency($e->getMessage());
            alert()->error('Gagal',$e->getMessage());
            return redirect()->route($this->route."edit",$id)->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $result = $this->service->where('id',$id)->first();
            $result->delete();
            alert()->html('Berhasil','Data berhasil dihapus','success');
            return redirect()->route($this->route."index");
        } catch (\Throwable $e) {
            Log::emergency($e->getMessage());
            alert()->error('Gagal',$e->getMessage());
            return redirect()->route($this->route."index");
        }
    }
}

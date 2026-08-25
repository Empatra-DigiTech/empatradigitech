<?php

namespace App\Http\Controllers\Patra;
use App\Http\Controllers\Controller;
use App\Models\CalculatorFeature;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class CalculatorFeatureController extends Controller
{
    protected $feature;
    protected $route = 'patra.calculator-feature.';

    public function __construct(){
        $this->route = "patra.calculator-feature.";
        $this->view = "patra.pages.calculator-feature.";
        $this->feature = new CalculatorFeature();
        Paginator::useBootstrap();
    }

    public function index(Request $request)
    {
        $table = $this->feature->orderBy("urutan","ASC")->orderBy("created_at","DESC")->paginate(15);
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
                'nama_fitur' => 'required',
                'harga_tambahan' => 'required|numeric|min:0',
            ]);

            $this->feature->create([
                'nama_fitur' => $request->nama_fitur,
                'harga_tambahan' => $request->harga_tambahan,
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
        $result = $this->feature->where('id',$id)->first();
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
                'nama_fitur' => 'required',
                'harga_tambahan' => 'required|numeric|min:0',
            ]);

            $result = $this->feature->where('id',$id)->first();
            if(!$result){
                throw new \Error("Data tidak ditemukan");
            }

            $result->update([
                'nama_fitur' => $request->nama_fitur,
                'harga_tambahan' => $request->harga_tambahan,
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
            $result = $this->feature->where('id',$id)->first();
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

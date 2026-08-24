<?php

namespace App\Http\Controllers\Patra;
use App\Http\Controllers\Controller;
use App\Models\Portofolio;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Portofolio\StoreRequest;
use App\Http\Requests\Portofolio\UpdateRequest;
use App\Helpers\UploadHelper;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Auth;

class PortofolioController extends Controller
{
    protected $portofolio;
    protected $route = 'patra.pages.portofolio.';

    public function __construct(){
        $this->route = "patra.portofolio.";
        $this->view = "patra.pages.portofolio.";
        $this->portofolio = new Portofolio();
        Paginator::useBootstrap();
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $table = $this->portofolio;

        if(!empty($search)){
            $table = $table->where(function($query2) use($search){
                $query2->where("title","like","%".$search."%");
            });
        }
        $table = $table->orderBy("created_at","DESC");
        $table = $table->paginate(10)->withQueryString();

        $data = [
            'table' => $table,
        ];

        return view($this->view."index",$data);
    }

    public function create()
    {
        return view($this->view."create");
    }

    public function store(StoreRequest $request)
    {
        try {
            $title = $request->title;
            $image = $request->file("image");

            if($image){
                $upload = UploadHelper::upload_file($image,'images',['jpeg','jpg','png','gif']);

                if($upload["IsError"] == TRUE){
                    throw new \Error($upload["Message"]);
                }

                $image = $upload["Path"];
                $create = $this->portofolio->create([
                    'title' => $title,
                    'portofolio-trixFields' => $request->input('portofolio-trixFields'),
                    'image' => $image,
                    'creator' => Auth::user()->name,
                    'klien' => $request->klien,
                    'industry' => $request->industry,
                    'layanan' => $request->layanan,
                    'brand' => $request->brand,
                    'tantangan' => $request->tantangan,
                    'solusi' => $request->solusi,
                    'fitur' => $request->fitur,
                    'hasil' => $request->hasil,
                    'demo_url' => $request->demo_url,
                ]);
            }
            alert()->html('Berhasil','Data berhasil ditambahkan','success');
            return redirect()->route($this->route."index");

        } catch (\Throwable $e) {
            Log::emergency($e->getMessage());
            alert()->error('Gagal',$e->getMessage());
            return redirect()->route($this->route."create")->withInput();
        }
    }

    public function show($id)
    {
        $result = $this->portofolio;
        $result = $result->where('id',$id);
        $result = $result->first();

        if(!$result){
            alert()->error('Gagal',"Data tidak ditemukan");
            return redirect()->route($this->route."index");
        }

        $data = [
            'result' => $result,
        ];

        return view($this->view."show",$data);
    }

    public function edit($id)
    {
        $result = $this->portofolio;
        $result = $result->where('id',$id);
        $result = $result->first();

        if(!$result){
            alert()->error('Gagal',"Data tidak ditemukan");
            return redirect()->route($this->route."index");
        }

        $data = [
            'result' => $result,
        ];

        return view($this->view."edit",$data);
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $result = $this->portofolio;
            $result = $result->where('id',$id);
            $result = $result->first();

            if(!$result){
                throw new \Error("Data tidak ditemukan");
            }

            $title = $request->title;
            $image = $request->file("image");

            if($image){
                $upload = UploadHelper::upload_file($image,'portofolio',['jpeg','jpg','png','gif']);

                if($upload["IsError"] == TRUE){
                    throw new \Error($upload["Message"]);
                }

                $image = $upload["Path"];
            }
            else{
                $image = $result->image;
            }

            $result->update([
                'title' => $title,
                'portofolio-trixFields' => $request->input('portofolio-trixFields'),
                'image' => $image,
                'klien' => $request->klien,
                'industry' => $request->industry,
                'layanan' => $request->layanan,
                'brand' => $request->brand,
                'tantangan' => $request->tantangan,
                'solusi' => $request->solusi,
                'fitur' => $request->fitur,
                'hasil' => $request->hasil,
                'demo_url' => $request->demo_url,
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
            $result = $this->portofolio;
            $result = $result->where('id',$id);
            $result = $result->first();

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

<?php

namespace App\Http\Controllers\Patra;
use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Faq\StoreRequest;
use App\Http\Requests\Faq\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class FaqController extends Controller
{
    protected $faq;
    protected $route = 'patra.pages.faq.';

    public function __construct(){
        $this->route = "patra.faq.";
        $this->view = "patra.pages.faq.";
        $this->faq = new Faq();
        Paginator::useBootstrap();
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $table = $this->faq;

        if(!empty($search)){
            $table = $table->where(function($query2) use($search){
                $query2->where("question","like","%".$search."%");
            });
        }
        $table = $table->orderBy("urutan","ASC")->orderBy("created_at","DESC");
        $table = $table->paginate(15)->withQueryString();

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
            $this->faq->create([
                'question' => $request->question,
                'answer' => $request->answer,
                'kategori' => $request->kategori,
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

    public function show($id)
    {
        $result = $this->faq->where('id',$id)->first();

        if(!$result){
            alert()->error('Gagal',"Data tidak ditemukan");
            return redirect()->route($this->route."index");
        }

        return view($this->view."show",['result' => $result]);
    }

    public function edit($id)
    {
        $result = $this->faq->where('id',$id)->first();

        if(!$result){
            alert()->error('Gagal',"Data tidak ditemukan");
            return redirect()->route($this->route."index");
        }

        return view($this->view."edit",['result' => $result]);
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $result = $this->faq->where('id',$id)->first();

            if(!$result){
                throw new \Error("Data tidak ditemukan");
            }

            $result->update([
                'question' => $request->question,
                'answer' => $request->answer,
                'kategori' => $request->kategori,
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
            $result = $this->faq->where('id',$id)->first();
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

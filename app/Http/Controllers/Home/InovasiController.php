<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inovasi;
use App\Models\DBPerhubungan;
use App\Models\Pengaturan;
use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;

class InovasiController extends Controller
{
    protected $inovasi;
    protected $route = 'home.pages.inovasi.';
    public function __construct(){
        $this->route = "home.inovasi.";
        $this->view = "home.pages.inovasi.";
        $this->inovasi = new inovasi();
        Paginator::useBootstrap();
    }
   
    public function index(Request $request)
    {
        $table_pengaturan = Pengaturan::first();
        $table_menu = Menu::all();
        $search = $request->search;
        $table = collect();
        $rfid = collect();
    
        if (!empty($search)) {
            $table = $this->inovasi
                ->where("title", "like", "%{$search}%")
                ->orderBy("created_at", "DESC")
                ->paginate(50)
                ->withQueryString();
    
            $rfid = \App\Models\DBPerhubungan::whereHas('pengujian', function ($query) use ($search) {
                    $query->where("nouji", "like", "%{$search}%");
                })
                ->with('pengujian')
                ->orderBy('datarfid.tgluji', 'desc')
                ->paginate(10)
                ->withQueryString();
            
                foreach ($rfid as $row) {
                    if (!empty($row->tgluji)) {
                        try {
                            $row->formatted_tgluji = Carbon::createFromFormat('dmY', $row->tgluji)->format('d-m-Y');
                        } catch (\Exception $e) {
                            $row->formatted_tgluji = '-'; // fallback kalau gagal parse
                        }
                    } else {
                        $row->formatted_tgluji = '-';
                    }
                }
        }
    
        return view($this->view . "index", [
            'table' => $table,
            'rfid' => $rfid,
            'table_pengaturan' => $table_pengaturan,
            'table_menu' => $table_menu,
        ]);
    }
    
         
    
    public function show($id){
        $table_pengaturan = Pengaturan::first();
        $table_menu = Menu::all();

        $result = $this->inovasi;
        $result = $result->where('id',$id);
        $result = $result->first();

        $except_result = $this->inovasi;
        $except_result = $except_result->where('id','!=',$id);
        $except_result = $except_result->orderBy("date","DESC");      //sort descending by time created data
        $except_result = $except_result->paginate(3);   //limit paginate only 10 data appears per load

        if(!$result){
            alert()->error('Gagal',"Data tidak ditemukan");
            return redirect()->route($this->route."index");
        }

        $data = [
            'result' => $result,
            'except_result' => $except_result,
            'table_pengaturan' => $table_pengaturan,
            'table_menu' => $table_menu,
        ];

        return view($this->view."show",$data);
    }
}

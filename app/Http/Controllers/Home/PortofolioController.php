<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Portofolio;
use App\Models\Pengaturan;
use App\Models\Menu;
use Illuminate\Pagination\Paginator;

class PortofolioController extends Controller
{
    protected $portofolio;
    protected $route = 'home.pages.portofolio.';
    public function __construct(){
        $this->route = "home.portofolio.";
        $this->view = "home.pages.portofolio.";
        $this->portofolio = new Portofolio();
        Paginator::useBootstrap();
    }

    public function index(Request $request)
    {
        $table_pengaturan = Pengaturan::first();
        $table_menu = Menu::all();
        $search = $request->search;

        $table = $this->portofolio;

        if(!empty($search)){
            $table = $table->where(function($query2) use($search){
                $query2->where("title","like","%".$search."%");
            });
        }
        $table = $table->orderBy("created_at","DESC");
        $table = $table->paginate(9)->withQueryString();

        $data = [
            'table' => $table,
            'table_pengaturan' => $table_pengaturan,
            'table_menu' => $table_menu,
        ];

        return view($this->view."index",$data);
    }
    public function show($id){
        $table_pengaturan = Pengaturan::first();
        $table_menu = Menu::all();

        $result = $this->portofolio;
        $result = $result->where('id',$id);
        $result = $result->first();

        $except_result = $this->portofolio;
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
        //view count in show portofolio
        views($result)->cooldown($minutes = 3)->record();

        return view($this->view."show",$data);
    }
}

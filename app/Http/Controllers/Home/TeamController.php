<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Pengaturan;
use Illuminate\Pagination\Paginator;
use App\Models\Menu;

class TeamController extends Controller
{
    protected $team;
    protected $route = 'home.pages.team.';
    public function __construct(){
        $this->route = "home.team.";
        $this->view = "home.pages.team.";
        $this->team = new Team();
        Paginator::useBootstrap();
    }

    public function index(Request $request)
    {
        $table_pengaturan = Pengaturan::first();
        $table_menu = Menu::all();
        $search = $request->search;

        $table = $this->team;

        if(!empty($search)){
            $table = $table->where(function($query2) use($search){
                $query2->where("title","like","%".$search."%");
            });
        }
        $table = $table->orderBy("created_at","ASC");
        $table = $table->paginate(100)->withQueryString();

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

        $result = $this->team;
        $result = $result->where('id',$id);
        $result = $result->first();

        $except_result = $this->team;
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
        //view count in show team
        views($result)->cooldown($minutes = 3)->record();

        return view($this->view."show",$data);
    }
}

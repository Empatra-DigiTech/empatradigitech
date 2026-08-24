<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Pengaturan;
use App\Models\Menu;
use Illuminate\Pagination\Paginator;

class BlogController extends Controller
{
    protected $blog;
    protected $route = 'home.pages.blog.';

    public function __construct(){
        $this->route = "home.blog.";
        $this->view = "home.pages.blog.";
        $this->blog = new Blog();
        Paginator::useBootstrap();
    }

    public function index(Request $request)
    {
        $table_pengaturan = Pengaturan::first();
        $table_menu = Menu::all();

        $search = $request->search;
        $kategori = $request->kategori;

        $table = $this->blog->where('is_published', true);

        if(!empty($search)){
            $table = $table->where(function($query2) use($search){
                $query2->where("title","like","%".$search."%")
                       ->orWhere("excerpt","like","%".$search."%");
            });
        }
        if(!empty($kategori)){
            $table = $table->where("kategori", $kategori);
        }
        $table = $table->orderBy("created_at","DESC");
        $table = $table->paginate(9)->withQueryString();

        $kategori_list = $this->blog->where('is_published', true)
            ->select('kategori')->distinct()->whereNotNull('kategori')->pluck('kategori');

        $data = [
            'table' => $table,
            'table_pengaturan' => $table_pengaturan,
            'table_menu' => $table_menu,
            'kategori_list' => $kategori_list,
            'kategori_active' => $kategori,
        ];

        return view($this->view."index",$data);
    }

    public function show($slug){
        $table_pengaturan = Pengaturan::first();
        $table_menu = Menu::all();

        $result = $this->blog;
        $result = $result->where('slug', $slug);
        $result = $result->where('is_published', true);
        $result = $result->first();

        if(!$result){
            alert()->error('Gagal',"Artikel tidak ditemukan");
            return redirect()->route($this->route."index");
        }

        $related_result = $this->blog;
        $related_result = $related_result->where('id','!=',$result->id);
        $related_result = $related_result->where('is_published', true);
        if(!empty($result->kategori)){
            $related_result = $related_result->where('kategori', $result->kategori);
        }
        $related_result = $related_result->orderBy("created_at","DESC");
        $related_result = $related_result->take(3)->get();

        $data = [
            'result' => $result,
            'related_result' => $related_result,
            'table_pengaturan' => $table_pengaturan,
            'table_menu' => $table_menu,
        ];

        // View count tracking (same pattern as Portofolio)
        views($result)->cooldown($minutes = 3)->record();

        return view($this->view."show",$data);
    }
}

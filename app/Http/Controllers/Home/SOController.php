<?php


namespace App\Http\Controllers\Home;

use Illuminate\Support\Facades\Cache;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SO;
use App\Models\Pengaturan;
use Illuminate\Pagination\Paginator;
use App\Models\Menu;

class SOController extends Controller
{
    protected $so;
    protected $route = 'home.pages.SO.';
    public function __construct(){
        $this->route = "home.so.";
        $this->view = "home.pages.SO.";
        $this->so = new SO();
        Paginator::useBootstrap();
    }
   
    public function index(Request $request)
    {   
        $table_pengaturan = \App\Support\PublicCache::pengaturan();
        $table_menu = \App\Support\PublicCache::menu();

        $result = $this->so;
        $result = $result->first();

        $data = [
            'result' => $result,
            'table_pengaturan' => $table_pengaturan,
            'table_menu' => $table_menu,
        ];

        return view($this->view."index",$data);
    }
}
<?php

namespace App\Http\Controllers\Home;

use Illuminate\Support\Facades\Cache;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengaturan;
use App\Models\Menu;

class ProfilController extends Controller
{
    // FIXED: Controller ini sebelumnya tidak ada sama sekali, padahal
    // routes/web.php sudah mendaftarkan route "home.profil.index" yang
    // mengarah ke ProfilController@index -> mengetik /profil langsung
    // akan menyebabkan fatal error (class not found).

    public function __construct(){
        $this->view = "home.pages.profil.";
        $this->route = "home.profil.";
    }

    public function index(Request $request)
    {
        $table_pengaturan = Cache::remember('pengaturan_first', 3600, fn () => Pengaturan::first());
        $table_menu = Cache::remember('menu_all', 3600, fn () => Menu::all());

        $data = [
            'table_pengaturan' => $table_pengaturan,
            'table_menu' => $table_menu,
        ];

        return view($this->view."index", $data);
    }
}

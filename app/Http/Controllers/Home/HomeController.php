<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kalender;
use App\Models\Tautan;
use App\Models\Portofolio;
use App\Models\Banner;
use App\Models\Pengaturan;
use App\Models\Layanan;
use App\Models\Paket;
use App\Models\Faq;
use App\Models\Menu;

class HomeController extends Controller
{
    // protected $tautan;

    public function __construct()
    {
        $this->view = "home.pages.home.";
        $this->tautan = new Tautan();
    }

    public function index(Request $request)
    {
        $table_tautan = Tautan::all();

        // FIXED: Changed from orderBy('date') to orderBy('created_at')
        $table_portofolio = Portofolio::orderBy('created_at', 'DESC')->take(6)->get();

        $table_banner = Banner::all();
        $table_layanan = Layanan::all();
        $table_pengaturan = Pengaturan::first();
        $table_menu = Menu::all();


        // Tambahkan data paket website dan app
        $paket_website = Paket::where('tipe', 'website')
            ->where('is_active', true)
            ->orderBy('urutan', 'ASC')
            ->orderBy('created_at', 'ASC')
            ->get();

        $paket_app = Paket::where('tipe', 'app')
            ->where('is_active', true)
            ->orderBy('urutan', 'ASC')
            ->orderBy('created_at', 'ASC')
            ->get();

        $table_faq = Faq::where('is_active', true)
            ->orderBy('urutan', 'ASC')
            ->orderBy('created_at', 'ASC')
            ->get();

        // Fetch the most viewed portofolio id
        $count_view = DB::table('views')
            ->select('viewable_id', DB::raw('COUNT(*) as total'))
            ->groupBy('viewable_id')
            ->orderBy('total', 'DESC')
            ->first();


        if ($count_view == null) {
            $data = [
                'table_tautan' => $table_tautan,
                'table_portofolio' => $table_portofolio,
                'table_banner' => $table_banner,
                'table_pengaturan' => $table_pengaturan,
                'table_layanan' => $table_layanan,
                'table_view' => null,
                'table_menu' => $table_menu,
                'paket_website' => $paket_website,
                'paket_app' => $paket_app,
                'table_faq' => $table_faq,
            ];

            return view($this->view . "index", $data);
        } else {
            // Get the id of the most viewed portofolio
            $id_count = $count_view->viewable_id;
            // Fetch the portofolio record with the most views
            $table_view = $table_portofolio->where('id', $id_count)->first();


            $data = [
                'table_tautan' => $table_tautan,
                'table_portofolio' => $table_portofolio,
                'table_banner' => $table_banner,
                'table_layanan' => $table_layanan,
                'table_pengaturan' => $table_pengaturan,
                'table_view' => $table_view,
                'count_view' => $count_view,
                'table_menu' => $table_menu,
                'paket_website' => $paket_website,
                'paket_app' => $paket_app,
                'table_faq' => $table_faq,
            ];

            return view($this->view . "index", $data);
        }
    }

    //function for calendar handler in json
    public function events()
    {
        $events = Kalender::all();
        return response()->json($events);
    }
}

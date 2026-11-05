<?php

namespace App\Http\Controllers\Home;

use Illuminate\Support\Facades\Cache;

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
use App\Models\Testimoni;
use App\Models\ClientLogo;
use App\Models\CalculatorService;
use App\Models\CalculatorFeature;
use App\Models\Menu;

class HomeController extends Controller
{
    // protected $tautan;

    public function __construct()
    {
        $this->view = "home.";
        $this->tautan = new Tautan();
    }

    public function index(Request $request)
    {
        $table_tautan = \App\Support\PublicCache::tautan();

        // FIXED: Changed from orderBy('date') to orderBy('created_at')
        $table_portofolio = Portofolio::orderBy('created_at', 'DESC')->take(6)->get();

        $table_banner = \App\Support\PublicCache::banner();
        $table_layanan = \App\Support\PublicCache::layanan();
        $table_pengaturan = \App\Support\PublicCache::pengaturan();
        $table_menu = \App\Support\PublicCache::menu();


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

        $table_testimoni = Testimoni::where('is_active', true)
            ->orderBy('urutan', 'ASC')
            ->orderBy('created_at', 'DESC')
            ->get();

        $table_client_logo = ClientLogo::where('is_active', true)
            ->orderBy('urutan', 'ASC')
            ->orderBy('created_at', 'ASC')
            ->get();

        $calculator_services = CalculatorService::where('is_active', true)
            ->orderBy('urutan', 'ASC')
            ->get();

        $calculator_features = CalculatorFeature::where('is_active', true)
            ->orderBy('urutan', 'ASC')
            ->get();

        // Catatan: query "konten terpopuler" (tabel views) dulu dijalankan di sini pada setiap
        // kunjungan beranda, tetapi hasilnya (table_view / count_view) tidak dipakai view mana pun.
        // Statistik tampilan konten kini ditangani DashboardService di panel admin.
        $data = [
            'table_tautan' => $table_tautan,
            'table_portofolio' => $table_portofolio,
            'table_banner' => $table_banner,
            'table_pengaturan' => $table_pengaturan,
            'table_layanan' => $table_layanan,
            'table_menu' => $table_menu,
            'paket_website' => $paket_website,
            'paket_app' => $paket_app,
            'table_faq' => $table_faq,
            'table_testimoni' => $table_testimoni,
            'table_client_logo' => $table_client_logo,
            'calculator_services' => $calculator_services,
            'calculator_features' => $calculator_features,
        ];

        return view($this->view . "index", $data);
    }

    //function for calendar handler in json
    public function events()
    {
        $events = \App\Support\PublicCache::kalender();
        return response()->json($events);
    }
}

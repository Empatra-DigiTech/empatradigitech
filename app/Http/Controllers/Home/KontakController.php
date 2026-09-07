<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Kontak\StoreRequest;
use App\Helpers\UploadHelper;
use App\Models\Kontak;
use App\Models\Pengaturan;
use App\Models\Menu;
use App\Models\Layanan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class KontakController extends Controller
{
    public function __construct(){
        $this->view = "home.pages.kontak.";
        $this->route = "home.kontak.";
        $this->kontak = new Kontak();
    }

    public function index(){
        // FIXED: view "home.pages.kontak.index" tidak pernah dibuat sebelumnya
        // (route home.kontak.index selalu fatal error). View sudah dibuat,
        // dan data navbar/footer (table_pengaturan, table_menu) yang sebelumnya
        // tidak pernah dikirim ke view ini sekarang disertakan juga.
        $table_pengaturan = Cache::remember('pengaturan_first', 3600, fn () => Pengaturan::first());
        $table_menu = Cache::remember('menu_all', 3600, fn () => Menu::all());
        $table_layanan = Layanan::orderBy('title', 'ASC')->get();

        $data = [
            'table_pengaturan' => $table_pengaturan,
            'table_menu' => $table_menu,
            'table_layanan' => $table_layanan,
        ];

        return view($this->view."index", $data);
    }

    public function store(StoreRequest $request){
        try {
            $name = $request->name;
            $image = $request->file("image");
            $email = $request->email;
            $subject = $request->subject;
            $message = $request->message;

            if($image){
                $upload = UploadHelper::upload_file($image,'images-kontak',['jpeg','jpg','png','gif']);

                if($upload["IsError"] == TRUE){
                    throw new Error($upload["Message"]);
                }

                $image = $upload["Path"];
                $create = $this->kontak->create([
                    'name' => $name,
                    'image' => $image,
                    'email'=> $email,
                    'subject'=> $subject,
                    'message' => $message,
                ]);
            }else{
                $create = $this->kontak->create([
                    'name' => $name,
                    'email'=> $email,
                    'subject'=> $subject,
                    'message' => $message,
                ]);
            }
            alert()->html('Berhasil','Pesan Berhasil dikirim!','success'); 
            return redirect()->route($this->route."index");

        } catch (\Throwable $e) {
            Log::emergency($e->getMessage());

            alert()->error('Gagal',$e->getMessage());

            // FIXED: sebelumnya redirect ke route "home.home.create" yang tidak
            // pernah didaftarkan (hanya home.kontak.index & home.kontak.store yang
            // ada) -> setiap kali terjadi error di sini (mis. email duplikat karena
            // kolom "email" unique), controller ini akan melempar
            // RouteNotFoundException baru di dalam blok catch-nya sendiri.
            return redirect()->route($this->route."index")->withInput();
        }
    }
}

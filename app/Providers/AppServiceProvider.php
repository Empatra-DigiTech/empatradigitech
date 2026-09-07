<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Tautan;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // FIXED: footer.blade.php di-include di master layout (jadi tampil di
        // SEMUA halaman publik), tapi hanya HomeController@index yang pernah
        // mengirim $table_tautan. View composer di sini memastikan data Tautan
        // selalu tersedia untuk footer di halaman manapun, tanpa perlu
        // menambahkan fetch manual ke belasan controller lain satu-satu.
        // Cache 1 jam, konsisten dengan pola Cache::remember yang sudah
        // dipakai untuk table_pengaturan & table_menu.
        View::composer('home.layouts.footer', function ($view) {
            $view->with('table_tautan', Cache::remember('tautan_all', 3600, fn () => Tautan::all()));
        });
    }
}

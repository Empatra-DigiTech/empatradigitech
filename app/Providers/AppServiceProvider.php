<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

use App\Models\Tautan;
use App\Models\Pengaturan;
use App\Models\Menu;
use App\Models\Layanan;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('home.layouts.footer', function ($view) {

            $view->with([

                'table_pengaturan' => Cache::remember(
                    'pengaturan_first',
                    3600,
                    fn () => Pengaturan::first()
                ),

                'table_menu' => Cache::remember(
                    'menu_all',
                    3600,
                    fn () => Menu::orderBy('created_at')->get()
                ),

                'table_tautan' => Cache::remember(
                    'tautan_all',
                    3600,
                    fn () => Tautan::all()
                ),

                'table_layanan' => Cache::remember(
                    'layanan_all',
                    3600,
                    fn () => Layanan::orderBy('title')->get()
                ),

            ]);

        });
    }
}
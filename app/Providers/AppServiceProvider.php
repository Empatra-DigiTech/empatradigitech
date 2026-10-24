<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Support\PublicCache;
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
        PublicCache::registerInvalidation();

        View::composer('home.layouts.footer', function ($view) {

            $view->with([
                'table_pengaturan' => PublicCache::pengaturan(),
                'table_menu' => PublicCache::menu(),
                'table_tautan' => PublicCache::tautan(),
                'table_layanan' => PublicCache::layananFooter(),
            ]);

        });
    }
}
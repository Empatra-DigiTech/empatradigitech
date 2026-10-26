<?php

use App\Http\Controllers\GambarController;
use Illuminate\Support\Facades\Route;
use App\Enums\RoleEnum;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//PAGE NOT FOUND
Route::fallback(function () {
    return view("home.pages.error.index");
});

//MIDDLEWARE
Route::group(["middleware" => ["patra.access"], "namespace" => "App\Http\Controllers\Patra", "as" => "patra.", "prefix" => "patra"], function () {
    Route::get("/", "PatraController@index")->name('patra.index');

    //PROFILE PAGE
    Route::group(["middleware" => ['role:' . implode('|', [RoleEnum::SuperAdmin, RoleEnum::Admin, RoleEnum::Moderator])], "as" => "profile.", "prefix" => "profile"], function () {
        Route::get('/', 'ProfileController@index')->name("index");
        Route::put('/', 'ProfileController@update')->name("update");
    });

    Route::group(["middleware" => ['role:' . implode('|', [RoleEnum::SuperAdmin, RoleEnum::Admin])], "as" => "menu.", "prefix" => "menu"], function () {
        Route::get('/', 'MenuController@index')->name("index");
        Route::get('/create', 'MenuController@create')->name("create");
        Route::post('/', 'MenuController@store')->name("store");
        Route::get('/{id}', 'MenuController@show')->name("show");
        Route::get('/{id}/edit', 'MenuController@edit')->name("edit");
        Route::put('/{id}', 'MenuController@update')->name("update");
        Route::delete('/{id}', 'MenuController@destroy')->name("destroy");
    });

    Route::group(["middleware" => ['role:' . implode('|', [RoleEnum::SuperAdmin, RoleEnum::Admin])], "as" => "informasi.", "prefix" => "informasi"], function () {
        Route::get("/", "InformasiController@index")->name("index");
        Route::get("/create", "InformasiController@create")->name("create");
        Route::post("/", "InformasiController@store")->name("store");
        Route::get('/{id}/edit', 'InformasiController@edit')->name("edit");
        Route::put('/{id}', 'InformasiController@update')->name("update");
        Route::delete('/{id}', 'InformasiController@destroy')->name("destroy");
    });

    Route::group(["middleware" => ['role:' . implode('|', [RoleEnum::SuperAdmin, RoleEnum::Admin, RoleEnum::Moderator])], "as" => "portofolio.", "prefix" => "portofolio"], function () {
        Route::get('/', 'PortofolioController@index')->name("index");
        Route::get('/create', 'PortofolioController@create')->name("create");
        Route::post('/', 'PortofolioController@store')->name("store");
        Route::get('/{id}', 'PortofolioController@show')->name("show");
        Route::get('/{id}/edit', 'PortofolioController@edit')->name("edit");
        Route::put('/{id}', 'PortofolioController@update')->name("update");
        Route::delete('/{id}', 'PortofolioController@destroy')->name("destroy");
    });

    Route::group(["middleware" => ['role:' . implode('|', [RoleEnum::SuperAdmin, RoleEnum::Admin])], "as" => "inovasi.", "prefix" => "inovasi"], function () {
        Route::get('/', 'InovasiController@index')->name("index");
        Route::get('/create', 'InovasiController@create')->name("create");
        Route::post('/', 'InovasiController@store')->name("store");
        Route::get('/{id}', 'InovasiController@show')->name("show");
        Route::get('/{id}/edit', 'InovasiController@edit')->name("edit");
        Route::put('/{id}', 'InovasiController@update')->name("update");
        Route::delete('/{id}', 'InovasiController@destroy')->name("destroy");
    });

    Route::group(["middleware" => ['role:' . implode('|', [RoleEnum::SuperAdmin, RoleEnum::Admin, RoleEnum::Moderator])], "as" => "blog.", "prefix" => "blog"], function () {
        Route::get('/', 'BlogController@index')->name("index");
        Route::get('/create', 'BlogController@create')->name("create");
        Route::post('/', 'BlogController@store')->name("store");
        Route::get('/{id}', 'BlogController@show')->name("show");
        Route::get('/{id}/edit', 'BlogController@edit')->name("edit");
        Route::put('/{id}', 'BlogController@update')->name("update");
        Route::delete('/{id}', 'BlogController@destroy')->name("destroy");
    });

    Route::group(["middleware" => ['role:' . implode('|', [RoleEnum::SuperAdmin, RoleEnum::Admin, RoleEnum::Moderator])], "as" => "faq.", "prefix" => "faq"], function () {
        Route::get('/', 'FaqController@index')->name("index");
        Route::get('/create', 'FaqController@create')->name("create");
        Route::post('/', 'FaqController@store')->name("store");
        Route::get('/{id}', 'FaqController@show')->name("show");
        Route::get('/{id}/edit', 'FaqController@edit')->name("edit");
        Route::put('/{id}', 'FaqController@update')->name("update");
        Route::delete('/{id}', 'FaqController@destroy')->name("destroy");
    });

    Route::group(["middleware" => ['role:' . implode('|', [RoleEnum::SuperAdmin, RoleEnum::Admin, RoleEnum::Moderator])], "as" => "testimoni.", "prefix" => "testimoni"], function () {
        Route::get('/', 'TestimoniController@index')->name("index");
        Route::get('/create', 'TestimoniController@create')->name("create");
        Route::post('/', 'TestimoniController@store')->name("store");
        Route::get('/{id}', 'TestimoniController@show')->name("show");
        Route::get('/{id}/edit', 'TestimoniController@edit')->name("edit");
        Route::put('/{id}', 'TestimoniController@update')->name("update");
        Route::delete('/{id}', 'TestimoniController@destroy')->name("destroy");
    });

    Route::group(["middleware" => ['role:' . implode('|', [RoleEnum::SuperAdmin, RoleEnum::Admin])], "as" => "client-logo.", "prefix" => "client-logo"], function () {
        Route::get('/', 'ClientLogoController@index')->name("index");
        Route::get('/create', 'ClientLogoController@create')->name("create");
        Route::post('/', 'ClientLogoController@store')->name("store");
        Route::get('/{id}', 'ClientLogoController@show')->name("show");
        Route::get('/{id}/edit', 'ClientLogoController@edit')->name("edit");
        Route::put('/{id}', 'ClientLogoController@update')->name("update");
        Route::delete('/{id}', 'ClientLogoController@destroy')->name("destroy");
    });

    Route::group(["middleware" => ['role:' . implode('|', [RoleEnum::SuperAdmin, RoleEnum::Admin])], "as" => "calculator-service.", "prefix" => "calculator-service"], function () {
        Route::get('/', 'CalculatorServiceController@index')->name("index");
        Route::get('/create', 'CalculatorServiceController@create')->name("create");
        Route::post('/', 'CalculatorServiceController@store')->name("store");
        Route::get('/{id}/edit', 'CalculatorServiceController@edit')->name("edit");
        Route::put('/{id}', 'CalculatorServiceController@update')->name("update");
        Route::delete('/{id}', 'CalculatorServiceController@destroy')->name("destroy");
    });

    Route::group(["middleware" => ['role:' . implode('|', [RoleEnum::SuperAdmin, RoleEnum::Admin])], "as" => "calculator-feature.", "prefix" => "calculator-feature"], function () {
        Route::get('/', 'CalculatorFeatureController@index')->name("index");
        Route::get('/create', 'CalculatorFeatureController@create')->name("create");
        Route::post('/', 'CalculatorFeatureController@store')->name("store");
        Route::get('/{id}/edit', 'CalculatorFeatureController@edit')->name("edit");
        Route::put('/{id}', 'CalculatorFeatureController@update')->name("update");
        Route::delete('/{id}', 'CalculatorFeatureController@destroy')->name("destroy");
    });

    Route::group(["middleware" => ['role:' . implode('|', [RoleEnum::SuperAdmin, RoleEnum::Admin])], "as" => "banner.", "prefix" => "banner"], function () {
        Route::get('/', 'BannerController@index')->name("index");
        Route::get('/create', 'BannerController@create')->name("create");
        Route::post('/', 'BannerController@store')->name("store");
        Route::get('/{id}', 'BannerController@show')->name("show");
        Route::get('/{id}/edit', 'BannerController@edit')->name("edit");
        Route::put('/{id}', 'BannerController@update')->name("update");
        Route::delete('/{id}', 'BannerController@destroy')->name("destroy");
    });

    Route::group(["middleware" => ['role:' . implode('|', [RoleEnum::SuperAdmin, RoleEnum::Admin])], "as" => "galeri.", "prefix" => "galeri"], function () {
        Route::get("/", "GaleriController@index")->name("index");
        Route::get("/create", "GaleriController@create")->name("create");
        Route::post("/", "GaleriController@store")->name("store");
        Route::get('/{id}', 'GaleriController@show')->name("show");
        Route::get('/{id}/edit', 'GaleriController@edit')->name("edit");
        Route::put('/{id}', 'GaleriController@update')->name("update");
        Route::delete('/{id}', 'GaleriController@destroy')->name("destroy");
    });

    Route::group(["middleware" => ['role:' . implode('|', [RoleEnum::SuperAdmin, RoleEnum::Admin])], "as" => "kalender.", "prefix" => "kalender"], function () {
        Route::get("/", "KalenderController@index")->name("index");
        Route::get("/create", "KalenderController@create")->name("create");
        Route::post("/", "KalenderController@store")->name("store");
        Route::get('/{id}', 'KalenderController@show')->name("show");
        Route::get('/{id}/edit', 'KalenderController@edit')->name("edit");
        Route::put('/{id}', 'KalenderController@update')->name("update");
        Route::delete('/{id}', 'KalenderController@destroy')->name("destroy");
    });

    Route::group(["middleware" => ['role:' . implode('|', [RoleEnum::SuperAdmin, RoleEnum::Admin])], "as" => "kontak.", "prefix" => "kontak"], function () {
        Route::get("/", "KontakController@index")->name("index");
        Route::get('/{id}', 'KontakController@show')->name("show");
        Route::delete('/{id}', 'KontakController@destroy')->name("destroy");
    });

    Route::group(["middleware" => ['role:' . implode('|', [RoleEnum::SuperAdmin, RoleEnum::Admin])], "as" => "layanan.", "prefix" => "layanan"], function () {
        Route::get('/', 'LayananController@index')->name("index");
        Route::get('/create', 'LayananController@create')->name("create");
        Route::post('/', 'LayananController@store')->name("store");
        Route::get('/{id}', 'LayananController@show')->name("show");
        Route::get('/{id}/edit', 'LayananController@edit')->name("edit");
        Route::put('/{id}', 'LayananController@update')->name("update");
        Route::delete('/{id}', 'LayananController@destroy')->name("destroy");
    });

    Route::group(["middleware" => ['role:' . implode('|', [RoleEnum::SuperAdmin, RoleEnum::Admin])], "as" => "paket.", "prefix" => "paket"], function () {
        Route::get('/', 'PaketController@index')->name("index");
        Route::get('/create', 'PaketController@create')->name("create");
        Route::post('/', 'PaketController@store')->name("store");
        Route::get('/{id}', 'PaketController@show')->name("show");
        Route::get('/{id}/edit', 'PaketController@edit')->name("edit");
        Route::put('/{id}', 'PaketController@update')->name("update");
        Route::delete('/{id}', 'PaketController@destroy')->name("destroy");
    });

    Route::group(["middleware" => ['role:' . implode('|', [RoleEnum::SuperAdmin, RoleEnum::Admin])], "as" => "tautan.", "prefix" => "tautan"], function () {
        Route::get("/", "TautanController@index")->name("index");
        Route::get("/create", "TautanController@create")->name("create");
        Route::post("/", "TautanController@store")->name("store");
        Route::get('/{id}', 'TautanController@show')->name("show");
        Route::get('/{id}/edit', 'TautanController@edit')->name("edit");
        Route::put('/{id}', 'TautanController@update')->name("update");
        Route::delete('/{id}', 'TautanController@destroy')->name("destroy");
    });

    Route::group(["middleware" => ['role:' . implode('|', [RoleEnum::SuperAdmin, RoleEnum::Admin])], "as" => "so.", "prefix" => "so"], function () {
        Route::get('/', 'SOController@index')->name("index");
        Route::put('/', 'SOController@update')->name("update");
    });

    Route::group(["middleware" => ['role:' . implode('|', [RoleEnum::SuperAdmin, RoleEnum::Admin])], "as" => "vm.", "prefix" => "vm"], function () {
        Route::get('/', 'VMController@index')->name("index");
        Route::put('/', 'VMController@update')->name("update");
    });

    Route::group(["middleware" => ['role:' . implode('|', [RoleEnum::SuperAdmin, RoleEnum::Admin])], "as" => "team.", "prefix" => "team"], function () {
        Route::get('/', 'TeamController@index')->name("index");
        Route::get('/create', 'TeamController@create')->name("create");
        Route::post('/', 'TeamController@store')->name("store");
        Route::get('/{id}', 'TeamController@show')->name("show");
        Route::get('/{id}/edit', 'TeamController@edit')->name("edit");
        Route::put('/{id}', 'TeamController@update')->name("update");
        Route::delete('/{id}', 'TeamController@destroy')->name("destroy");
    });

    Route::group(["middleware" => ['role:' . implode('|', [RoleEnum::SuperAdmin])], "as" => "pengaturan.", "prefix" => "pengaturan"], function () {
        Route::get("/", "PengaturanController@index")->name("index");
        Route::put('/', 'PengaturanController@update')->name("update");
    });

Route::group(["middleware" => ['role:' . implode('|', [RoleEnum::SuperAdmin, RoleEnum::Admin])], "as" => "invoice.", "prefix" => "invoice"], function () {
    Route::get('/', 'InvoiceController@index')->name("index");
    Route::get('/create', 'InvoiceController@create')->name("create");
    Route::post('/', 'InvoiceController@store')->name("store");
    Route::get('/{id}', 'InvoiceController@show')->name("show");
    Route::get('/{id}/edit', 'InvoiceController@edit')->name("edit");
    Route::put('/{id}', 'InvoiceController@update')->name("update");
    Route::delete('/{id}', 'InvoiceController@destroy')->name("destroy");
    Route::get('/{id}/download', 'InvoiceController@download')->name("download");
});

    Route::group(["middleware" => ['role:' . implode('|', [RoleEnum::SuperAdmin])], "as" => "finance.", "prefix" => "finance"], function () {
        Route::get('/', 'FinanceTransactionController@index')->name("index");
        Route::get('/trash', 'FinanceTransactionController@trash')->name("trash");
        Route::post('/{id}/restore', 'FinanceTransactionController@restore')->name("restore");
        Route::get('/period', 'FinancePeriodController@index')->name("period.index");
        Route::post('/period/close', 'FinancePeriodController@close')->name("period.close");
        Route::post('/period/reopen', 'FinancePeriodController@reopen')->name("period.reopen");
        Route::get('/import', 'FinanceImportController@create')->name("import.create");
        Route::post('/import/preview', 'FinanceImportController@preview')->name("import.preview");
        Route::post('/import', 'FinanceImportController@store')->name("import.store");
        Route::get('/create', 'FinanceTransactionController@create')->name("create");
        Route::post('/', 'FinanceTransactionController@store')->name("store");
        Route::get('/{id}/edit', 'FinanceTransactionController@edit')->name("edit");
        Route::put('/{id}', 'FinanceTransactionController@update')->name("update");
        Route::delete('/{id}', 'FinanceTransactionController@destroy')->name("destroy");
    });

    Route::group(["middleware" => ['role:' . implode('|', [RoleEnum::SuperAdmin])], "as" => "finance-category.", "prefix" => "finance-category"], function () {
        Route::get('/', 'FinanceCategoryController@index')->name("index");
        Route::get('/create', 'FinanceCategoryController@create')->name("create");
        Route::post('/', 'FinanceCategoryController@store')->name("store");
        Route::get('/{id}/edit', 'FinanceCategoryController@edit')->name("edit");
        Route::put('/{id}', 'FinanceCategoryController@update')->name("update");
        Route::delete('/{id}', 'FinanceCategoryController@destroy')->name("destroy");
    });

    Route::group(["middleware" => ['role:' . implode('|', [RoleEnum::SuperAdmin])], "as" => "users.", "prefix" => "users"], function () {
        Route::get("/", "UserController@index")->name("index");
        Route::get('/create', 'UserController@create')->name("create");
        Route::post('/', 'UserController@store')->name("store");
        Route::get('/{id}', 'UserController@show')->name("show");
        Route::get('/{id}/edit', 'UserController@edit')->name("edit");
        Route::put('/{id}', 'UserController@update')->name("update");
        Route::delete('/{id}', 'UserController@destroy')->name("destroy");
        Route::patch('/{id}', 'UserController@restore')->name("restore");
        Route::get('/{id}/impersonate', 'UserController@impersonate')->name("impersonate");
    });
});

//AUTH
Route::group(["namespace" => "App\Http\Controllers\Auth", "as" => "auth.", "prefix" => "auth"], function () {
    Route::group(["as" => "login.", "prefix" => "login"], function () {
        Route::get("/", "LoginController@index")->name("index");
        Route::post("/", "LoginController@post")->name("post")->middleware('throttle:6,1');
    });

    Route::get("/logout", "LogoutController@logout")->name('logout');

    Route::group(["as" => "forgot_pw.", "prefix" => "forgot_pw"], function () {
        Route::get("/", "ForgotPasswordController@index")->name("index");
        Route::post("/", "ForgotPasswordController@store")->name("store");
    });

    Route::group(["as" => "reset_pw.", "prefix" => "reset_pw"], function () {
        Route::get('/{token}', 'ResetPasswordController@index')->name('index');
        Route::post('/', 'ResetPasswordController@post')->name('post');
    });
});




//HOME
Route::group(["namespace" => "App\Http\Controllers\Home", "as" => "home."], function () {
    Route::get("/", "HomeController@index")->name('home.index');
    Route::get("/events", "HomeController@events")->name("events");

    Route::get("/{title}/show", "MenuController@show");


    Route::group(["as" => "kontak.", "prefix" => "kontak"], function () {
        Route::post("/", "KontakController@store")->name("store")->middleware('throttle:10,1');
    });


    Route::group(["as" => "layanan.", "prefix" => "layanan"], function () {
        Route::get("/{id}", "LayananController@show")->name("show");
    });

    Route::group(["as" => "profil.", "prefix" => "profil"], function () {
        Route::get("/", "ProfilController@index")->name("index");
    });

    Route::group(["as" => "portofolio.", "prefix" => "portfolio"], function () {
        Route::get("/", "PortofolioController@index")->name("index");
        Route::get("/{id}", "PortofolioController@show")->name("show");
    });

    Route::group(["as" => "inovasi.", "prefix" => "inovasi"], function () {
        Route::get("/", "InovasiController@index")->name("index");
        Route::get("/{id}", "InovasiController@show")->name("show");
    });

    Route::group(["as" => "blog.", "prefix" => "blog"], function () {
        Route::get("/", "BlogController@index")->name("index");
        Route::get("/{slug}", "BlogController@show")->name("show");
    });

    Route::group(["as" => "galeri.", "prefix" => "galeri"], function () {
        Route::get("/", "GaleriController@index")->name("index");
        Route::get('/{id}', 'GaleriController@show')->name("show");
    });

    Route::group(["as" => "informasi.", "prefix" => "informasi"], function () {
        Route::get("/", "InformasiController@index")->name("index");
    });

    Route::group(["as" => "kontak.", "prefix" => "kontak"], function () {
        Route::get("/", "KontakController@index")->name("index");
    });

    Route::group(["as" => "SO.", "prefix" => "SO"], function () {
        Route::get("/", "SOController@index")->name("index");
        Route::get("/{id}", "SOController@show")->name("show");
    });

    Route::group(["as" => "team.", "prefix" => "team"], function () {
        Route::get("/", "TeamController@index")->name("index");
        Route::get("/{id}", "TeamController@show")->name("show");
    });

    Route::group(["as" => "VM.", "prefix" => "VM"], function () {
        Route::get("/", "VMController@index")->name("index");
    });
});

// IMAGE SHOW
Route::get('/inovasi/image/{id}/{jenis}', [GambarController::class, 'show'])->name('image.show');





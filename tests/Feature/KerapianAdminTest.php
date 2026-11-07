<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\Blog;
use App\Models\FinancePeriod;
use App\Models\FinanceTransaction;
use App\Models\Galeri;
use App\Models\Paket;
use App\Models\SO;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/** Patch kerapian: C6, C8-C13, C15, B4. */
class KerapianAdminTest extends TestCase
{
    private $sa;

    private function sa()
    {
        return $this->sa ??= $this->superAdmin();
    }

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        Storage::fake('public');
    }

    // ---- C6 + C12
    public function test_banner_tersimpan_di_folder_banner(): void
    {
        $this->actingAs($this->sa())->post('/patra/banner', [
            'title' => 'Promo', 'image' => UploadedFile::fake()->image('a.jpg', 200, 100),
        ]);

        $banner = Banner::where('title', 'Promo')->first();
        $this->assertNotNull($banner);
        $this->assertStringContainsString('banner', $banner->image);
    }

    // ---- C8
    public function test_fitur_paket_disimpan_sebagai_list_walau_ada_isian_kosong(): void
    {
        $this->actingAs($this->sa())->post('/patra/paket', [
            'tipe' => 'website', 'nama_paket' => 'Basic', 'harga' => 100000,
            'fitur' => ['A', '', 'C'],
        ]);

        $paket = Paket::where('nama_paket', 'Basic')->first();
        $this->assertNotNull($paket);
        $this->assertSame(['A', 'C'], $paket->fitur);
        $this->assertTrue(array_is_list($paket->fitur));
    }

    // ---- C9
    public function test_galeri_update_menerima_video_seperti_store(): void
    {
        $sa = $this->sa();
        $g = Galeri::create(['title' => 'G', 'description' => 'd', 'date' => '2026-01-01', 'image' => 'galeri/x.jpg']);

        $this->actingAs($sa)->put("/patra/galeri/{$g->id}", [
            'title' => 'G2', 'description' => 'd', 'date' => '2026-01-01',
            'image' => UploadedFile::fake()->create('v.mp4', 3000, 'video/mp4'),
        ]);

        $this->assertSame('G2', $g->fresh()->title);
        $this->assertStringEndsWith('.mp4', $g->fresh()->image);
    }

    public function test_galeri_store_wajib_deskripsi_dan_tanggal(): void
    {
        $awal = Galeri::count();

        $this->actingAs($this->sa())->post('/patra/galeri', [
            'title' => 'G', 'image' => UploadedFile::fake()->image('a.jpg'),
        ]);

        $this->assertSame($awal, Galeri::count());
    }

    // ---- C10
    public function test_judul_terlalu_panjang_ditolak(): void
    {
        $awal = Banner::count();

        $this->actingAs($this->sa())->post('/patra/banner', [
            'title' => str_repeat('a', 300), 'image' => UploadedFile::fake()->image('a.jpg'),
        ]);

        $this->assertSame($awal, Banner::count());
    }

    public function test_kategori_blog_harus_dari_daftar(): void
    {
        $awal = Blog::count();

        $this->actingAs($this->sa())->post('/patra/blog', [
            'title' => 'B', 'kategori' => 'Sembarang', 'excerpt' => 'x',
            'image' => UploadedFile::fake()->image('a.jpg'),
        ]);

        $this->assertSame($awal, Blog::count());
    }

    public function test_kalender_akhir_tidak_boleh_sebelum_mulai(): void
    {
        $this->actingAs($this->sa())->post('/patra/kalender', ['title' => 'K', 'start' => '2026-05-10', 'end' => '2026-05-01']);

        $this->assertDatabaseMissing('kalenders', ['title' => 'K']);

        $this->actingAs($this->sa())->post('/patra/kalender', ['title' => 'K2', 'start' => '2026-05-10', 'end' => '2026-05-10']);

        $this->assertDatabaseHas('kalenders', ['title' => 'K2']);
    }

    // ---- C11 + C12
    public function test_so_tabel_kosong_tidak_error_dan_memakai_folder_so(): void
    {
        SO::query()->delete();

        $this->actingAs($this->sa())->post('/patra/so', ['_method' => 'PUT', 'image' => UploadedFile::fake()->image('a.jpg')]);

        $so = SO::first();
        $this->assertNotNull($so);
        $this->assertStringContainsString('so/', $so->image);
        $this->assertStringNotContainsString('kesalahan pada server', (string) session('alert.config'));
    }

    // ---- C13
    private function profil(array $o = []): array
    {
        $u = $this->sa();

        return array_merge(['name' => $u->name, 'email' => $u->email, 'phone' => '0812'], $o);
    }

    public function test_ganti_password_wajib_password_saat_ini(): void
    {
        $u = $this->sa();
        $u->update(['password' => Hash::make('rahasia-lama')]);
        $hash = $u->fresh()->password;

        $this->actingAs($u)->put('/patra/profile', $this->profil(['password' => 'baru-12345', 'password_confirmation' => 'baru-12345']));
        $this->assertSame($hash, $u->fresh()->password);

        $this->actingAs($u)->put('/patra/profile', $this->profil(['current_password' => 'salah', 'password' => 'baru-12345', 'password_confirmation' => 'baru-12345']));
        $this->assertSame($hash, $u->fresh()->password);

        $this->actingAs($u)->put('/patra/profile', $this->profil(['current_password' => 'rahasia-lama', 'password' => 'baru-12345', 'password_confirmation' => 'baru-12345']));
        $this->assertTrue(Hash::check('baru-12345', $u->fresh()->password));
    }

    public function test_ganti_email_wajib_password_saat_ini_tapi_nama_tidak(): void
    {
        $u = $this->sa();
        $u->update(['password' => Hash::make('rahasia-lama')]);
        $emailAwal = $u->email;

        $this->actingAs($u)->put('/patra/profile', $this->profil(['email' => 'baru@test.local']));
        $this->assertSame($emailAwal, $u->fresh()->email);

        $this->actingAs($u)->put('/patra/profile', $this->profil(['name' => 'Nama Baru']));
        $this->assertSame('Nama Baru', $u->fresh()->name);

        $this->actingAs($u)->put('/patra/profile', $this->profil(['email' => 'baru@test.local', 'current_password' => 'rahasia-lama']));
        $this->assertSame('baru@test.local', $u->fresh()->email);
    }

    // ---- C15
    public function test_controller_tidak_membuat_properti_dinamis(): void
    {
        $dinamis = [];
        set_error_handler(function ($no, $msg) use (&$dinamis) {
            if (str_contains($msg, 'dynamic property')) {
                $dinamis[] = $msg;
            }

            return true;
        });

        try {
            foreach (glob(app_path('Http/Controllers/*/*Controller.php')) as $file) {
                $class = 'App\\Http\\Controllers\\' . basename(dirname($file)) . '\\' . basename($file, '.php');
                if (class_exists($class) && ! (new \ReflectionClass($class))->isAbstract()) {
                    try {
                        app()->make($class);
                    } catch (\Throwable $e) {
                        // controller dengan dependensi khusus: tidak relevan untuk pemeriksaan ini
                    }
                }
            }
        } finally {
            restore_error_handler();
        }

        $this->assertSame([], $dinamis);
    }

    // ---- B4
    public function test_ringkasan_periode_benar_dan_tidak_satu_query_per_periode(): void
    {
        $sa = $this->sa();
        $kat = \App\Models\FinanceCategory::create(['name' => 'K', 'type' => 'masuk', 'is_active' => true]);
        $mk = fn ($tgl, $type, $amt) => FinanceTransaction::createWithAutoNumber([
            'date' => $tgl, 'description' => 'x', 'finance_category_id' => $kat->id, 'type' => $type, 'amount' => $amt, 'payment_method' => 'Tunai',
        ]);
        $mk('2026-01-31', 'masuk', 100);
        $mk('2026-01-05', 'keluar', 30);
        $mk('2026-02-01', 'masuk', 7);
        foreach (['2026-01', '2026-02', '2026-03', '2026-04'] as $p) {
            FinancePeriod::create(['period' => $p, 'is_closed' => true]);
        }

        DB::enableQueryLog();
        $res = $this->actingAs($sa)->get('/patra/finance/period');
        $transaksiQ = collect(DB::getQueryLog())->filter(fn ($q) => str_contains($q['query'], 'finance_transactions'))->count();

        $res->assertOk();
        $this->assertLessThanOrEqual(1, $transaksiQ);

        $summary = $res->viewData('periods')->keyBy('period');
        $this->assertEquals(2, $summary['2026-01']->summary->jumlah);
        $this->assertEquals(100, $summary['2026-01']->summary->masuk);
        $this->assertEquals(30, $summary['2026-01']->summary->keluar);
        $this->assertEquals(1, $summary['2026-02']->summary->jumlah);
        $this->assertEquals(0, $summary['2026-03']->summary->jumlah);
    }
}

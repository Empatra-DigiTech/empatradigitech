<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\Kalender;
use App\Models\Layanan;
use App\Models\Menu;
use App\Models\Pengaturan;
use App\Models\Tautan;
use App\Support\PublicCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/** Fase 6.2: cache data publik harus cepat tetapi tidak pernah menampilkan data basi setelah admin mengubah data. */
class PublicCacheTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_banner_dibaca_dari_cache_lalu_dibatalkan_saat_data_berubah(): void
    {
        $b = Banner::create(['title' => 'Satu', 'image' => 'a.png']);
        $this->assertCount(1, PublicCache::banner());

        // Tulis lewat query builder (tanpa observer): cache TIDAK boleh ikut berubah -> membuktikan cache aktif.
        DB::table('banners')->insert(['title' => 'Dua', 'image' => 'b.png', 'created_at' => now(), 'updated_at' => now()]);
        $this->assertCount(1, PublicCache::banner(), 'harus dari cache');

        // Tulis lewat Eloquent: cache dibatalkan otomatis.
        $b->update(['title' => 'Satu (ubah)']);
        $this->assertCount(2, PublicCache::banner());

        $b->delete();
        $this->assertCount(1, PublicCache::banner());
    }

    public function test_setiap_model_ter_cache_membatalkan_cache_saat_create_update_delete(): void
    {
        $cases = [
            'tautan' => [fn () => Tautan::create(['title' => 'T', 'url' => 'https://x.test']), fn () => PublicCache::tautan()],
            'layanan_home' => [fn () => Layanan::create(['title' => 'L', 'image' => 'l.png']), fn () => PublicCache::layanan()],
            'layanan_footer' => [fn () => Layanan::create(['title' => 'L2', 'image' => 'l.png']), fn () => PublicCache::layananFooter()],
            'kalender' => [fn () => Kalender::create(['title' => 'K', 'start' => '2026-01-01', 'end' => '2026-01-02']), fn () => PublicCache::kalender()],
            'menu' => [fn () => Menu::create(['title' => 'M', 'parent' => 0]), fn () => PublicCache::menu()],
        ];

        foreach ($cases as $name => [$make, $read]) {
            Cache::flush();
            $before = count($read());

            $row = $make();
            $this->assertCount($before + 1, $read(), "{$name}: create harus membatalkan cache");

            $row->update(['title' => 'diubah']);
            $this->assertSame('diubah', $read()->firstWhere('id', $row->id)->title, "{$name}: update harus membatalkan cache");

            $row->delete();
            $this->assertCount($before, $read(), "{$name}: delete harus membatalkan cache");
        }
    }

    public function test_pengaturan_diperbarui_setelah_diubah(): void
    {
        $p = Pengaturan::first() ?? Pengaturan::create(['website_name' => 'Awal', 'website_logo' => 'l.png', 'website_maps' => '', 'website_motto' => '-', 'website_phone' => '1', 'website_email' => 'a@b.test', 'website_address' => '-']);
        $p->update(['website_name' => 'Lama']);
        $this->assertSame('Lama', PublicCache::pengaturan()->website_name);

        $p->update(['website_name' => 'Baru']);
        $this->assertSame('Baru', PublicCache::pengaturan()->website_name);
    }

    public function test_halaman_depan_menampilkan_perubahan_terbaru_setelah_data_diubah(): void
    {
        $t = Tautan::create(['title' => 'TautanLamaXYZ', 'url' => 'https://lama.test']);
        $this->get('/')->assertOk()->assertSee('TautanLamaXYZ');

        $t->update(['title' => 'TautanBaruXYZ']);
        $this->get('/')->assertOk()->assertSee('TautanBaruXYZ')->assertDontSee('TautanLamaXYZ');

        $l = Layanan::create(['title' => 'LayananLamaXYZ', 'image' => 'l.png']);
        $this->get('/')->assertSee('LayananLamaXYZ');
        $l->update(['title' => 'LayananBaruXYZ']);
        $this->get('/')->assertSee('LayananBaruXYZ')->assertDontSee('LayananLamaXYZ');
    }

    public function test_request_kedua_ke_beranda_tidak_lagi_query_data_ter_cache(): void
    {
        Banner::create(['title' => 'B', 'image' => 'b.png']);
        $this->get('/')->assertOk(); // memanaskan cache

        DB::flushQueryLog();
        DB::enableQueryLog();
        $this->get('/')->assertOk();
        $sql = implode("\n", array_column(DB::getQueryLog(), 'query'));

        foreach (['banners', 'tautans', 'layanans', 'pengaturans', 'menus'] as $table) {
            $this->assertStringNotContainsString("\"{$table}\"", $sql, "tabel {$table} seharusnya dilayani dari cache");
        }
    }

    public function test_flush_membersihkan_semua_key(): void
    {
        Banner::create(['title' => 'B', 'image' => 'b.png']);
        PublicCache::banner();
        $this->assertTrue(Cache::has('banner_all'));

        PublicCache::flush();
        $this->assertFalse(Cache::has('banner_all'));
    }

    public function test_tidak_ada_cache_remember_data_publik_di_luar_public_cache(): void
    {
        // Mencegah key yang sama dipakai dua query berbeda (bug lama `menu_all`).
        $root = dirname(__DIR__, 2) . '/app';
        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root));
        $offenders = [];
        foreach ($it as $f) {
            if ($f->getExtension() !== 'php' || str_ends_with($f->getPathname(), 'Support/PublicCache.php')) {
                continue;
            }
            if (preg_match("/Cache::remember\\(\\s*['\"](pengaturan_first|menu_all|tautan_all|layanan_all|layanan_home|banner_all|kalender_all)['\"]/", file_get_contents($f->getPathname()))) {
                $offenders[] = str_replace($root, 'app', $f->getPathname());
            }
        }
        $this->assertSame([], $offenders, 'Gunakan App\\Support\\PublicCache: ' . implode(', ', $offenders));
    }
}

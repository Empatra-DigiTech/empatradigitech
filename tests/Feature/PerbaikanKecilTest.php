<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\Kontak;
use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/** R1-R11 dari ANALISIS_PROYEK_EMPATRA.md. */
class PerbaikanKecilTest extends TestCase
{
    // ---------- R1
    public function test_logout_hanya_lewat_post(): void
    {
        $this->actingAs($this->superAdmin());

        // GET tidak lagi memutus sesi (jatuh ke halaman 404).
        $this->get('/auth/logout')->assertNotFound();
        $this->assertAuthenticated();

        $this->post('/auth/logout')->assertRedirect(route('auth.login.index'));
        $this->assertGuest();
    }

    public function test_tombol_logout_di_topbar_adalah_form_post_dengan_csrf(): void
    {
        $html = $this->actingAs($this->superAdmin())->get('/patra')->getContent();

        $this->assertMatchesRegularExpression('#<form action="[^"]*/auth/logout" method="POST"#', $html);
    }

    // ---------- R2
    public function test_password_lama_kurang_dari_8_karakter_masih_bisa_login(): void
    {
        $u = $this->superAdmin();
        $u->update(['password' => 'pendek1']);

        $this->post('/auth/login', ['email' => $u->email, 'password' => 'pendek1']);

        $this->assertAuthenticated();
    }

    // ---------- R3
    public function test_beranda_tidak_lagi_menjalankan_query_tabel_views(): void
    {
        $queries = [];
        \DB::listen(function ($q) use (&$queries) { $queries[] = $q->sql; });

        $this->get('/')->assertOk();

        $this->assertEmpty(array_filter($queries, fn ($sql) => str_contains($sql, 'from "views"') || str_contains($sql, 'from `views`')));
    }

    // ---------- R4
    public function test_nomor_invoice_berikutnya_memakai_urutan_terbesar(): void
    {
        $ym = date('Ym');
        $this->assertSame("INV-{$ym}-0001", Invoice::generateInvoiceNumber());

        $make = fn (string $no) => \DB::table('invoices')->insert([
            'invoice_number' => $no, 'invoice_date' => now()->toDateString(),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // Baris terakhir (id terbesar) bernomor aneh hasil edit manual; urutan asli 0007.
        $make("INV-{$ym}-0007");
        $make('MANUAL-XYZ');

        $this->assertSame("INV-{$ym}-0008", Invoice::generateInvoiceNumber());
    }

    // ---------- R5
    public function test_update_user_soft_delete_bisa_disimpan_dan_tidak_bisa_hapus_diri_sendiri(): void
    {
        $me = $this->superAdmin();
        $target = $this->moderator();
        $target->delete();

        $this->actingAs($me)->put(route('patra.users.update', $target->id), [
            'name' => 'Nama Baru', 'email' => $target->email, 'roles' => 'Moderator', 'status' => 1,
        ])->assertRedirect(route('patra.users.index'));
        $this->assertSame('Nama Baru', User::withTrashed()->find($target->id)->name);

        $this->delete(route('patra.users.destroy', $me->id))->assertRedirect(route('patra.users.index'));
        $this->assertNotNull(User::find($me->id), 'user tidak boleh menghapus dirinya sendiri');
    }

    // ---------- R6
    public function test_config_locale_hanya_satu_definisi(): void
    {
        $src = file_get_contents(base_path('config/app.php'));

        $this->assertSame(1, preg_match_all("/^\s*'locale'\s*=>/m", $src));
        $this->assertSame(1, preg_match_all("/^\s*'faker_locale'\s*=>/m", $src));
        $this->assertSame('id', config('app.locale'));
    }

    // ---------- R7
    public function test_layout_admin_lang_id_trix_sekali_dan_footer_bersih(): void
    {
        $html = $this->actingAs($this->superAdmin())->get('/patra/layanan/create')->assertOk()->getContent();

        $this->assertStringContainsString('<html lang="id">', $html);
        $this->assertSame(1, substr_count($html, 'trix.js'), 'Trix hanya boleh dimuat sekali');
        $this->assertSame(1, substr_count($html, 'trix.css'));
        $this->assertStringNotContainsString('2014-2021', $html);
        $this->assertStringNotContainsString('<col ', $html);
    }

    // ---------- R8
    public function test_aset_admin_yang_tidak_dipakai_tidak_dimuat(): void
    {
        $html = $this->actingAs($this->superAdmin())->get('/patra')->getContent();

        foreach (['jqvmap', 'jquery.vmap', 'summernote', 'jquery-knob', 'daterangepicker', 'ionicons', 'boxicons.js'] as $needle) {
            $this->assertStringNotContainsString($needle, $html, $needle);
        }
        $this->assertStringContainsString('tempusdominus', $html);
        $this->assertStringContainsString('moment.min.js', $html);
    }

    // ---------- R9
    public function test_avatar_default_tidak_memakai_layanan_eksternal(): void
    {
        $u = User::factory()->create(['name' => 'Budi Santoso']);

        $url = $u->avatarUrl();
        $this->assertStringStartsWith('data:image/svg+xml;base64,', $url);
        $this->assertStringContainsString('>BS<', base64_decode(substr($url, strlen('data:image/svg+xml;base64,'))));

        $this->assertStringNotContainsString('dicebear', $this->actingAs($this->superAdmin())->get('/patra')->getContent());
    }

    public function test_avatar_nama_berisi_markup_tidak_menjadi_xss(): void
    {
        $u = User::factory()->create(['name' => '<script>alert(1)</script>']);

        $svg = base64_decode(substr($u->avatarUrl(), strlen('data:image/svg+xml;base64,')));

        $this->assertStringNotContainsString('<script', $svg);
    }

    // ---------- R10
    public function test_update_pengaturan_saat_tabel_kosong_memberi_pesan_jelas_lalu_berhasil_dengan_logo(): void
    {
        Storage::fake('public');
        Pengaturan::query()->delete();
        $payload = [
            'website_name' => 'Empatra', 'website_phone' => '0812', 'website_address' => 'Malang',
            'website_email' => 'a@b.test', 'website_motto' => 'm', 'website_maps' => 'x',
            'stat_projects' => 1, 'stat_clients' => 1, 'stat_industries' => 1, 'stat_years_experience' => 1,
        ];
        $admin = $this->superAdmin();

        // Tanpa logo: tidak crash, pesan menjelaskan apa yang kurang.
        $this->actingAs($admin)->put(route('patra.pengaturan.update'), $payload)->assertRedirect();
        $this->assertStringContainsString('Logo website wajib diunggah', json_encode(session('alert.config')));
        $this->assertSame(0, Pengaturan::count());

        // Dengan logo: baris dibuat.
        $this->put(route('patra.pengaturan.update'), $payload + ['website_logo' => \Illuminate\Http\UploadedFile::fake()->image('logo.png')])
            ->assertRedirect();
        $this->assertSame('Empatra', Pengaturan::first()?->website_name);
    }

    public function test_halaman_tidak_ditemukan_membalas_status_404(): void
    {
        $this->get('/halaman/yang/tidak-ada')->assertNotFound();
    }

    // ---------- R11
    public function test_hapus_kontak_ikut_menghapus_gambar_dan_id_hilang_tidak_error(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('images-kontak/a.png', 'x');
        $k = Kontak::create(['name' => 'A', 'email' => 'a@b.test', 'subject' => 's', 'message' => 'm', 'image' => 'images-kontak/a.png']);

        $admin = $this->superAdmin();
        $this->actingAs($admin)->delete(route('patra.kontak.destroy', $k->id))->assertRedirect();

        $this->assertNull(Kontak::find($k->id));
        Storage::disk('public')->assertMissing('images-kontak/a.png');

        $this->delete(route('patra.kontak.destroy', 9999))->assertRedirect(route('patra.kontak.index'));
    }
}

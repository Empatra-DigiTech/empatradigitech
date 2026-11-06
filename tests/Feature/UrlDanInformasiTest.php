<?php

namespace Tests\Feature;

use App\Models\Informasi;
use App\Models\Tautan;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

/** C1 (Informasi "berhasil" palsu) dan C2 (validasi URL di semua modul). */
class UrlDanInformasiTest extends TestCase
{
    // ---------- C1
    public function test_store_informasi_tanpa_file_dan_link_ditolak_bukan_berhasil_palsu(): void
    {
        $this->actingAs($this->superAdmin())->post(route('patra.informasi.store'), ['title' => 'Tanpa isi']);

        $this->assertSame(0, Informasi::count());
        $this->assertStringNotContainsString('berhasil', strtolower(json_encode(session('alert.config'))));
    }

    public function test_store_informasi_dengan_link_tersimpan(): void
    {
        $this->actingAs($this->superAdmin())
            ->post(route('patra.informasi.store'), ['title' => 'Panduan', 'file_link' => 'https://contoh.test/panduan'])
            ->assertRedirect(route('patra.informasi.index'));

        $row = Informasi::first();
        $this->assertSame('link', $row->type);
        $this->assertSame('https://contoh.test/panduan', $row->file_link);
    }

    public function test_store_informasi_dengan_file_tersimpan(): void
    {
        Storage::fake();

        $this->actingAs($this->superAdmin())
            ->post(route('patra.informasi.store'), ['title' => 'Brosur', 'file_path' => UploadedFile::fake()->create('brosur.pdf', 50, 'application/pdf')])
            ->assertRedirect(route('patra.informasi.index'));

        $this->assertSame('file', Informasi::first()->type);
    }

    public function test_update_hanya_judul_tersimpan_dan_link_lama_dipertahankan(): void
    {
        $row = Informasi::create(['title' => 'Judul Lama', 'type' => 'link', 'file_link' => 'https://lama.test']);

        $this->actingAs($this->superAdmin())
            ->put(route('patra.informasi.update', $row->id), ['title' => 'Judul Baru'])
            ->assertRedirect(route('patra.informasi.index'));

        $row->refresh();
        $this->assertSame('Judul Baru', $row->title);
        $this->assertSame('https://lama.test', $row->file_link);
        $this->assertSame('link', $row->type);
    }

    public function test_hapus_informasi_yang_sudah_tidak_ada_tidak_error(): void
    {
        $this->actingAs($this->superAdmin())
            ->delete(route('patra.informasi.destroy', 9999))
            ->assertRedirect(route('patra.informasi.index'));
    }

    // ---------- C2
    public function test_link_informasi_berbahaya_ditolak(): void
    {
        $admin = $this->superAdmin();

        $this->actingAs($admin)->post(route('patra.informasi.store'), ['title' => 'X', 'file_link' => 'javascript:alert(1)']);

        $this->assertSame(0, Informasi::count());
    }

    public function test_url_tautan_berbahaya_ditolak_dan_aman_diterima(): void
    {
        $admin = $this->superAdmin();

        $this->actingAs($admin)->post(route('patra.tautan.store'), ['title' => 'Jahat', 'url' => 'javascript:alert(1)']);
        $this->post(route('patra.tautan.store'), ['title' => 'Tanpa skema', 'url' => 'www.contoh.test']);
        $this->assertSame(0, Tautan::count());

        $this->post(route('patra.tautan.store'), ['title' => 'Aman', 'url' => 'https://contoh.test']);
        $this->assertSame(1, Tautan::count());
    }

    public function test_update_tautan_dengan_url_berbahaya_ditolak(): void
    {
        $t = Tautan::create(['title' => 'T', 'url' => 'https://aman.test']);

        $this->actingAs($this->superAdmin())->put(route('patra.tautan.update', $t->id), ['title' => 'T', 'url' => 'data:text/html,x']);

        $this->assertSame('https://aman.test', $t->fresh()->url);
    }

    public function test_aturan_url_dipasang_di_portofolio_clientlogo_dan_team(): void
    {
        $cek = function (string $requestClass, string $field, string $nilai): bool {
            $rules = (new $requestClass)->rules();

            return Validator::make([$field => $nilai], [$field => $rules[$field]])->passes();
        };

        foreach ([
            [\App\Http\Requests\Portofolio\StoreRequest::class, 'demo_url'],
            [\App\Http\Requests\Portofolio\UpdateRequest::class, 'demo_url'],
            [\App\Http\Requests\ClientLogo\StoreRequest::class, 'website_url'],
            [\App\Http\Requests\ClientLogo\UpdateRequest::class, 'website_url'],
            [\App\Http\Requests\Team\StoreRequest::class, 'linkedin'],
            [\App\Http\Requests\Team\UpdateRequest::class, 'instagram'],
        ] as [$class, $field]) {
            $this->assertFalse($cek($class, $field, 'javascript:alert(1)'), "$class::$field menerima javascript:");
            $this->assertFalse($cek($class, $field, 'data:text/html;base64,PHNjcmlwdD4='), "$class::$field menerima data:");
            $this->assertTrue($cek($class, $field, 'https://contoh.test/profil'), "$class::$field menolak https");
            $this->assertTrue($cek($class, $field, ''), "$class::$field harus tetap boleh kosong");
        }
    }
}

<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\Informasi;
use App\Models\Kontak;
use App\Models\Layanan;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/** C4: berkas lama harus ikut terhapus dari storage saat diganti atau datanya dihapus. */
class HapusBerkasLamaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    private function berkas(string $path): string
    {
        Storage::disk('public')->put($path, 'isi');

        return $path;
    }

    public function test_ganti_gambar_menghapus_gambar_lama_dan_menyimpan_yang_baru(): void
    {
        $banner = Banner::create(['title' => 'B', 'image' => $this->berkas('image/lama.png')]);
        $this->berkas('image/baru.png');

        $banner->update(['image' => 'image/baru.png']);

        Storage::disk('public')->assertMissing('image/lama.png');
        Storage::disk('public')->assertExists('image/baru.png');
    }

    public function test_update_tanpa_mengganti_gambar_tidak_menghapus_apa_pun(): void
    {
        $banner = Banner::create(['title' => 'B', 'image' => $this->berkas('image/tetap.png')]);

        $banner->update(['title' => 'Judul Baru']);

        Storage::disk('public')->assertExists('image/tetap.png');
    }

    public function test_hapus_data_menghapus_berkasnya(): void
    {
        $layanan = Layanan::create(['title' => 'L', 'image' => $this->berkas('images/layanan.png')]);

        $layanan->delete();

        Storage::disk('public')->assertMissing('images/layanan.png');
    }

    public function test_berkas_yang_masih_dipakai_baris_lain_tidak_dihapus(): void
    {
        $a = Banner::create(['title' => 'A', 'image' => $this->berkas('image/bersama.png')]);
        Banner::create(['title' => 'B', 'image' => 'image/bersama.png']);

        $a->delete();

        Storage::disk('public')->assertExists('image/bersama.png');
    }

    public function test_nilai_berbahaya_atau_bukan_path_storage_diabaikan(): void
    {
        $this->berkas('rahasia.txt');

        foreach (['../rahasia.txt', '/rahasia.txt', 'https://contoh.test/a.png', 'image/../rahasia.txt'] as $nilai) {
            $b = Banner::create(['title' => 'X', 'image' => $nilai]);
            $b->delete();
        }

        Storage::disk('public')->assertExists('rahasia.txt');
    }

    public function test_informasi_dari_file_ke_link_menghapus_file_lama_lewat_controller(): void
    {
        $row = Informasi::create(['title' => 'I', 'type' => 'file', 'file_path' => $this->berkas('file_path/brosur.pdf')]);

        $this->actingAs($this->superAdmin())
            ->put(route('patra.informasi.update', $row->id), ['title' => 'I', 'file_link' => 'https://contoh.test/brosur'])
            ->assertRedirect(route('patra.informasi.index'));

        Storage::disk('public')->assertMissing('file_path/brosur.pdf');
        $this->assertNull($row->fresh()->file_path);
    }

    public function test_avatar_user_lama_dihapus_saat_diganti_dan_soft_delete_tidak_menghapus(): void
    {
        $u = User::factory()->create(['avatar' => $this->berkas('user-avatar/lama.png')]);
        $this->berkas('user-avatar/baru.png');

        $u->update(['avatar' => 'user-avatar/baru.png']);
        Storage::disk('public')->assertMissing('user-avatar/lama.png');

        $u->delete(); // soft delete: akun masih bisa dipulihkan, avatar harus tetap ada
        Storage::disk('public')->assertExists('user-avatar/baru.png');

        $u->forceDelete();
        Storage::disk('public')->assertMissing('user-avatar/baru.png');
    }

    public function test_kontak_hapus_tidak_error_walau_berkas_sudah_tidak_ada(): void
    {
        $k = Kontak::create(['name' => 'A', 'email' => 'a@b.test', 'subject' => 's', 'message' => 'm', 'image' => 'images-kontak/hilang.png']);

        $k->delete();

        $this->assertNull(Kontak::find($k->id));
    }
}

<?php

namespace Tests\Feature;

use App\Models\Kontak;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    private function payload(array $over = []): array
    {
        return array_merge([
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'subject' => 'Tanya layanan',
            'message' => 'Halo, saya ingin bertanya.',
        ], $over);
    }

    public function test_pesan_valid_tersimpan(): void
    {
        $this->post('/kontak', $this->payload())->assertRedirect();

        $this->assertDatabaseHas('kontaks', ['email' => 'budi@example.com', 'subject' => 'Tanya layanan']);
    }

    public function test_field_wajib_divalidasi(): void
    {
        $this->post('/kontak', [])->assertRedirect();

        $this->assertSame(0, Kontak::count());
    }

    public function test_email_tidak_valid_ditolak(): void
    {
        $this->post('/kontak', $this->payload(['email' => 'bukan-email']))->assertRedirect();

        $this->assertSame(0, Kontak::count());
    }

    public function test_subject_maksimal_100_karakter(): void
    {
        $this->post('/kontak', $this->payload(['subject' => str_repeat('a', 101)]));

        $this->assertSame(0, Kontak::count());
    }

    public function test_file_bukan_gambar_ditolak(): void
    {
        Storage::fake();

        $this->post('/kontak', $this->payload([
            'image' => UploadedFile::fake()->create('shell.php', 10, 'application/x-php'),
        ]));

        $this->assertSame(0, Kontak::count());
    }

    public function test_gambar_valid_tersimpan(): void
    {
        Storage::fake();

        $this->post('/kontak', $this->payload([
            'image' => UploadedFile::fake()->image('foto.png'),
        ]));

        $this->assertSame(1, Kontak::count());
        $this->assertNotNull(Kontak::first()->image);
    }

    public function test_pengunjung_yang_sama_boleh_kirim_lebih_dari_sekali(): void
    {

        $this->post('/kontak', $this->payload());
        $this->post('/kontak', $this->payload(['subject' => 'Kedua']));

        $this->assertSame(2, Kontak::count());
    }

    public function test_form_kontak_dibatasi_rate_limit(): void
    {

        for ($i = 0; $i < 15; $i++) {
            $res = $this->post('/kontak', $this->payload(['email' => "u{$i}@example.com"]));
        }
        $res->assertStatus(429);
    }
}

<?php

namespace Tests\Feature;

use Anhskohbo\NoCaptcha\NoCaptcha;
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

    public function test_nama_email_dan_pesan_dibatasi_panjangnya(): void
    {
        $this->post('/kontak', $this->payload(['name' => str_repeat('a', 101)]));
        $this->post('/kontak', $this->payload(['email' => str_repeat('a', 250) . '@example.com']));
        $this->post('/kontak', $this->payload(['message' => str_repeat('a', 2001)]));

        $this->assertSame(0, Kontak::count());

        $this->post('/kontak', $this->payload(['message' => str_repeat('a', 2000)]));
        $this->assertSame(1, Kontak::count());
    }

    private function aktifkanCaptcha(bool $lolos): void
    {
        config(['captcha.secret' => 'secret-uji', 'captcha.sitekey' => 'sitekey-uji']);

        $fake = \Mockery::mock(NoCaptcha::class);
        $fake->shouldReceive('verifyResponse')->andReturn($lolos);
        $this->app->instance('captcha', $fake);
    }

    public function test_captcha_wajib_bila_kunci_terisi(): void
    {
        $this->aktifkanCaptcha(true);

        $this->post('/kontak', $this->payload())->assertRedirect();

        $this->assertSame(0, Kontak::count(), 'tanpa g-recaptcha-response harus ditolak');
    }

    public function test_captcha_salah_ditolak(): void
    {
        $this->aktifkanCaptcha(false);

        $this->post('/kontak', $this->payload(['g-recaptcha-response' => 'token-palsu']))->assertRedirect();

        $this->assertSame(0, Kontak::count());
    }

    public function test_captcha_benar_diterima(): void
    {
        $this->aktifkanCaptcha(true);

        $this->post('/kontak', $this->payload(['g-recaptcha-response' => 'token-sah']))->assertRedirect();

        $this->assertSame(1, Kontak::count());
    }

    public function test_tanpa_kunci_captcha_form_tetap_berjalan(): void
    {
        $this->assertSame('', (string) config('captcha.secret'));

        $this->post('/kontak', $this->payload())->assertRedirect();

        $this->assertSame(1, Kontak::count());
    }

    public function test_partial_captcha_tampil_hanya_bila_kunci_terisi(): void
    {
        $this->assertStringNotContainsString('g-recaptcha', view('home.partials.captcha')->render());

        config(['captcha.secret' => 'secret-uji', 'captcha.sitekey' => 'sitekey-uji']);
        $this->app->forgetInstance('captcha');

        $html = view('home.partials.captcha')->render();
        $this->assertStringContainsString('g-recaptcha', $html);
        $this->assertStringContainsString('sitekey-uji', $html);
        $this->assertStringNotContainsString('secret-uji', $html);
    }

    public function test_form_kontak_dibatasi_rate_limit(): void
    {

        for ($i = 0; $i < 15; $i++) {
            $res = $this->post('/kontak', $this->payload(['email' => "u{$i}@example.com"]));
        }
        $res->assertStatus(429);
    }
}

<?php

namespace Tests\Feature;

use Anhskohbo\NoCaptcha\NoCaptcha;
use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_halaman_login_tampil(): void
    {
        $this->get('/auth/login')->assertOk();
    }

    public function test_login_valid_masuk_ke_patra(): void
    {
        $this->superAdmin();

        $this->post('/auth/login', ['email' => 'superadmin@test.local', 'password' => 'password123'])
            ->assertRedirect();

        $this->assertAuthenticated();
    }

    public function test_login_password_salah_ditolak(): void
    {
        $this->superAdmin();

        $this->post('/auth/login', ['email' => 'superadmin@test.local', 'password' => 'salahsalah'])
            ->assertRedirect();

        $this->assertGuest();
    }

    public function test_login_tanpa_field_ditolak_validasi(): void
    {
        $this->post('/auth/login', [])->assertRedirect();
        $this->assertGuest();
    }

    public function test_user_tanpa_role_tidak_boleh_login(): void
    {
        User::factory()->create(['email' => 'biasa@test.local', 'password' => bcrypt('password123')]);

        $this->post('/auth/login', ['email' => 'biasa@test.local', 'password' => 'password123']);

        $this->assertGuest();
    }

    public function test_user_nonaktif_tidak_boleh_login_walau_password_benar(): void
    {
        $u = $this->superAdmin();
        $u->update(['status' => 0]);

        $this->post('/auth/login', ['email' => 'superadmin@test.local', 'password' => 'password123'])
            ->assertRedirect();

        $this->assertGuest();
    }

    public function test_user_aktif_tetap_bisa_login(): void
    {
        $u = $this->superAdmin();
        $u->update(['status' => 1]);

        $this->post('/auth/login', ['email' => 'superadmin@test.local', 'password' => 'password123']);

        $this->assertAuthenticated();
    }

    public function test_sesi_user_yang_dinonaktifkan_diputus_saat_membuka_patra(): void
    {
        $u = $this->superAdmin();
        $this->actingAs($u)->get('/patra')->assertOk();

        $u->update(['status' => 0]);

        $this->actingAs($u->fresh())->get('/patra')->assertRedirect(route('auth.login.index'));
        $this->assertGuest();
    }

    private function aktifkanCaptcha(bool $lolos): void
    {
        config(['captcha.secret' => 'secret-uji', 'captcha.sitekey' => 'sitekey-uji']);

        $fake = \Mockery::mock(NoCaptcha::class);
        $fake->shouldReceive('verifyResponse')->andReturn($lolos);
        $this->app->instance('captcha', $fake);
    }

    public function test_halaman_login_menampilkan_captcha_hanya_bila_kunci_terisi(): void
    {
        $this->get('/auth/login')->assertOk()->assertDontSee('g-recaptcha', false);

        config(['captcha.secret' => 'secret-uji', 'captcha.sitekey' => 'sitekey-uji']);
        $this->app->forgetInstance('captcha');

        $this->get('/auth/login')->assertOk()->assertSee('g-recaptcha', false)->assertSee('sitekey-uji', false)->assertDontSee('secret-uji', false);
    }

    public function test_login_tanpa_captcha_ditolak_bila_captcha_aktif(): void
    {
        $this->superAdmin();
        $this->aktifkanCaptcha(true);

        $this->post('/auth/login', ['email' => 'superadmin@test.local', 'password' => 'password123'])->assertRedirect();

        $this->assertGuest();
    }

    public function test_login_dengan_captcha_salah_ditolak_walau_password_benar(): void
    {
        $this->superAdmin();
        $this->aktifkanCaptcha(false);

        $this->post('/auth/login', ['email' => 'superadmin@test.local', 'password' => 'password123', 'g-recaptcha-response' => 'palsu'])->assertRedirect();

        $this->assertGuest();
    }

    public function test_login_dengan_captcha_benar_berhasil(): void
    {
        $this->superAdmin();
        $this->aktifkanCaptcha(true);

        $this->post('/auth/login', ['email' => 'superadmin@test.local', 'password' => 'password123', 'g-recaptcha-response' => 'sah']);

        $this->assertAuthenticated();
    }

    public function test_remember_me_terkirim_dan_membuat_cookie_ingat_saya(): void
    {
        $this->superAdmin();

        $this->get('/auth/login')->assertSee('name="rememberme"', false);

        $res = $this->post('/auth/login', ['email' => 'superadmin@test.local', 'password' => 'password123', 'rememberme' => '1']);

        $this->assertNotEmpty(collect($res->headers->getCookies())->first(fn ($c) => str_starts_with($c->getName(), 'remember_web_')));
    }

    public function test_patra_butuh_login(): void
    {
        $this->get('/patra')->assertRedirect();
    }

    public function test_login_dibatasi_setelah_banyak_percobaan(): void
    {

        $this->superAdmin();
        for ($i = 0; $i < 10; $i++) {
            $res = $this->post('/auth/login', ['email' => 'superadmin@test.local', 'password' => 'salahsalah']);
        }
        $res->assertStatus(429);
    }
}

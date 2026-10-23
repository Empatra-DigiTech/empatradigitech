<?php

namespace Tests\Feature;

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

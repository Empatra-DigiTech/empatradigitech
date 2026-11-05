<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/** Fase 7: token reset password kedaluwarsa, sekali pakai, disimpan sebagai hash, dan tidak membuat 500 saat tidak valid. */
class ResetPasswordTest extends TestCase
{
    private function token(string $email, string $token = 'tok123', $createdAt = null): void
    {
        DB::table('password_reset_tokens')->updateOrInsert(['email' => $email], ['token' => hash('sha256', $token), 'created_at' => $createdAt ?? now()]);
    }

    private function payload(string $email, string $token = 'tok123'): array
    {
        return ['token' => $token, 'email' => $email, 'password' => 'PasswordBaru123', 'password_confirmation' => 'PasswordBaru123'];
    }

    public function test_token_valid_menampilkan_form(): void
    {
        $u = $this->superAdmin();
        $this->token($u->email);

        $this->get('/auth/reset_pw/tok123')->assertOk()->assertSee('Ganti Password Baru');
    }

    public function test_token_tidak_dikenal_atau_kedaluwarsa_ditolak(): void
    {
        $u = $this->superAdmin();
        $this->token($u->email, 'lama', now()->subMinutes(120));

        // Tidak lagi menampilkan teks mentah "null data": diarahkan ke form lupa password dengan pesan error.
        $this->get('/auth/reset_pw/tidak-ada')->assertRedirect(route('auth.forgot_pw.index'));
        $this->get('/auth/reset_pw/lama')->assertRedirect(route('auth.forgot_pw.index'));
    }

    public function test_token_mentah_di_database_tidak_diterima(): void
    {
        $u = $this->superAdmin();
        // Menyalin isi kolom token (hash) dari database tidak boleh cukup untuk reset password.
        $this->token($u->email, 'tok123');
        $hash = hash('sha256', 'tok123');

        $this->get('/auth/reset_pw/' . $hash)->assertRedirect(route('auth.forgot_pw.index'));
        $this->post('/auth/reset_pw', $this->payload($u->email, $hash))->assertRedirect(route('auth.forgot_pw.index'));
        $this->assertFalse(Hash::check('PasswordBaru123', $u->fresh()->password));
    }

    public function test_reset_berhasil_mengubah_password_dan_token_sekali_pakai(): void
    {
        $u = $this->superAdmin();
        $this->token($u->email);

        $this->post('/auth/reset_pw', $this->payload($u->email))->assertOk();

        $this->assertTrue(Hash::check('PasswordBaru123', $u->fresh()->password));
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $u->email]);

        // Token yang sama tidak bisa dipakai lagi.
        $this->post('/auth/reset_pw', array_merge($this->payload($u->email), ['password' => 'LainLagi12345', 'password_confirmation' => 'LainLagi12345']))
            ->assertRedirect(route('auth.forgot_pw.index'));
        $this->assertTrue(Hash::check('PasswordBaru123', $u->fresh()->password));
    }

    public function test_post_dengan_token_palsu_tidak_error_500(): void
    {
        $u = $this->superAdmin();

        $this->post('/auth/reset_pw', $this->payload($u->email, 'palsu'))->assertRedirect(route('auth.forgot_pw.index'));
        $this->assertFalse(Hash::check('PasswordBaru123', $u->fresh()->password));
    }

    public function test_email_form_harus_milik_token(): void
    {
        $a = $this->superAdmin();
        $b = $this->admin();
        $this->token($a->email);

        $this->post('/auth/reset_pw', $this->payload($b->email))->assertRedirect();
        $this->assertFalse(Hash::check('PasswordBaru123', $b->fresh()->password));
        $this->assertFalse(Hash::check('PasswordBaru123', $a->fresh()->password));
    }
}

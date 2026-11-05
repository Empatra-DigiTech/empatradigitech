<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/** M3: alur lupa password tidak boleh membocorkan email terdaftar, wajib di-throttle, dan token disimpan sebagai hash. */
class ForgotPasswordTest extends TestCase
{
    private function sentMessages()
    {
        return Mail::mailer()->getSymfonyTransport()->messages();
    }

    public function test_email_terdaftar_dikirimi_link_reset_dan_token_disimpan_hash(): void
    {
        $u = $this->superAdmin();

        $this->post('/auth/forgot_pw', ['email' => $u->email])->assertRedirect(route('auth.login.index'));

        $this->assertCount(1, $this->sentMessages());

        $body = $this->sentMessages()->first()->getOriginalMessage()->getBody()->bodyToString();
        $body = quoted_printable_decode($body); // badan email terenkode quoted-printable
        $this->assertSame(1, preg_match('#/auth/reset_pw/([A-Za-z0-9]{60})#', $body, $m), 'link reset tidak ada di email');

        $stored = DB::table('password_reset_tokens')->where('email', $u->email)->value('token');
        $this->assertNotSame($m[1], $stored, 'token mentah tidak boleh tersimpan di database');
        $this->assertSame(hash('sha256', $m[1]), $stored);

        // Link di email benar-benar bisa dipakai.
        $this->get('/auth/reset_pw/' . $m[1])->assertOk()->assertSee('Ganti Password Baru');
    }

    public function test_pesan_sama_untuk_email_terdaftar_dan_tidak_terdaftar(): void
    {
        $u = $this->superAdmin();

        $a = $this->post('/auth/forgot_pw', ['email' => $u->email]);
        $messageA = session('alert.config');
        $b = $this->post('/auth/forgot_pw', ['email' => 'tidak-ada@test.local']);
        $messageB = session('alert.config');

        $a->assertRedirect(route('auth.login.index'));
        $b->assertRedirect(route('auth.login.index'));
        $this->assertSame($messageA, $messageB);
        $this->assertCount(1, $this->sentMessages(), 'email tidak terdaftar tidak boleh dikirimi email');
    }

    public function test_user_nonaktif_tidak_dikirimi_email(): void
    {
        $u = $this->superAdmin();
        $u->update(['status' => 0]);

        $this->post('/auth/forgot_pw', ['email' => $u->email])->assertRedirect(route('auth.login.index'));

        $this->assertCount(0, $this->sentMessages());
    }

    public function test_pengirim_tidak_lagi_hardcode(): void
    {
        config(['mail.from.address' => 'noreply@empatra.test', 'mail.from.name' => 'Empatra Digitech']);
        $u = $this->superAdmin();

        $this->post('/auth/forgot_pw', ['email' => $u->email]);

        $msg = $this->sentMessages()->first()->getOriginalMessage();
        $this->assertSame('noreply@empatra.test', $msg->getFrom()[0]->getAddress());
        $this->assertStringNotContainsString('KIR', $msg->getSubject());
    }

    public function test_format_email_salah_ditolak_tanpa_mengirim_email(): void
    {
        $this->post('/auth/forgot_pw', ['email' => 'bukan-email'])->assertRedirect(route('auth.forgot_pw.index'));

        $this->assertCount(0, $this->sentMessages());
    }

    public function test_forgot_password_dibatasi_setelah_banyak_permintaan(): void
    {
        for ($i = 0; $i < 8; $i++) {
            $res = $this->post('/auth/forgot_pw', ['email' => 'siapa@test.local']);
        }

        $res->assertStatus(429);
    }
}

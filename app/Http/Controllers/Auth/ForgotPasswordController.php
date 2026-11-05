<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{

    public function __construct() {
        $this->view = "auth.";
        $this->user = new User();
    }

    public function index(){
        return view($this->view."forgot_pw");
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ], [
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Format email tidak valid',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter',
        ]);

        if ($validator->fails()) {
            alert()->html('Gagal', implode('<br>', $validator->errors()->all()), 'error');
            return redirect()->route('auth.forgot_pw.index')->withInput();
        }

        $email = $request->email;

        $user = $this->user->where('email', $email)->first();

        // Hanya akun yang ada dan aktif yang dikirimi email. Pesan ke pengguna selalu
        // sama supaya halaman ini tidak bisa dipakai untuk mengecek email terdaftar.
        if ($user && (int) $user->status === UserEnum::STATUS_ACTIVE) {
            try {
                $token = Str::random(60);

                // Yang disimpan di database hanya hash token. Token asli hanya ada di email.
                DB::table('password_reset_tokens')->updateOrInsert(
                    ['email' => $user->email],
                    ['token' => hash('sha256', $token), 'created_at' => now()]
                );

                $mail_data = [
                    'recipient' => $user->email,
                    'token'     => $token,
                ];

                // Pengirim mengikuti MAIL_FROM_ADDRESS / MAIL_FROM_NAME di .env.
                Mail::send('auth.message', $mail_data, function ($message) use ($mail_data) {
                    $message->to($mail_data['recipient'])
                            ->subject('Konfirmasi Lupa Password Akun Website EMPATRA DIGITECH');
                });
            } catch (\Throwable $e) {
                Log::error($e->getMessage(), ['exception' => $e]);
            }
        }

        alert()->html('','Jika email terdaftar, link reset password telah dikirim. Silakan periksa email Anda.','info');
        return redirect()->route('auth.login.index');
    }
}

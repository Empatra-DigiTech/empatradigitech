<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\User;
use Error;

class ResetPasswordController extends Controller
{
    public function __construct(){
        $this->view = "auth.";
        $this->user = new User();
    }

    /**
     * Ambil token yang MASIH berlaku (satu query, bukan seluruh tabel).
     * Masa berlaku mengikuti config auth.passwords.users.expire (menit, bawaan 60).
     */
    private function validToken(?string $token)
    {
        if (empty($token)) {
            return null;
        }

        $target = DB::table('password_reset_tokens')->where('token', $token)->first();
        if (! $target) {
            return null;
        }

        $expire = (int) config('auth.passwords.users.expire', 60);
        if ($target->created_at && Carbon::parse($target->created_at)->addMinutes($expire)->isPast()) {
            return null;
        }

        return $target;
    }

    public function index(Request $request){
        $target = $this->validToken($request->token);

        if ($target) {
            return view($this->view."reset_pw", ['target' => $target]);
        }

        return "null data";
    }

    public function post(Request $request){

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $target = $this->validToken($request->token);
        if (! $target) {
            alert()->html('Gagal','Link reset password tidak valid atau sudah kedaluwarsa. Silakan minta link baru.','error');
            return redirect()->route('auth.forgot_pw.index');
        }

        $result = $this->user->where('email', $target->email)->first();

        // Email di form harus milik token ini.
        if ($result && $request->email == $result->email) {
            $result->update([
                'password' => bcrypt($request->password),
                'remember_token' => Str::random(60),
            ]);

            // Token hanya berlaku sekali.
            DB::table('password_reset_tokens')->where('email', $target->email)->delete();

            alert()->html('Berhasil','Password berhasil diubah','success');
            return view($this->view."login");
        }

        alert()->html('Gagal','Terjadi Kesalahan Saat Update Password!','danger');
        return redirect()->back();
    }
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lab404\Impersonate\Models\Impersonate;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use App\Enums\UserEnum;
use App\Enums\RoleEnum;
use Illuminate\Contracts\Auth\CanResetPassword;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes, Loggable, Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'status',
        'email_verified_at',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function status()
    {
        $return = null;
        switch ($this->status) {
            case UserEnum::STATUS_ACTIVE:
                $return = (object) [
                    'class' => 'success',
                    'msg' => 'Aktif',
                ];
                break;

            case UserEnum::STATUS_INACTIVE:
                $return = (object) [
                    'class' => 'warning',
                    'msg' => 'Tidak Aktif',
                ];
                break;
        }

        return $return;
    }

    /**
     * URL avatar: foto yang diunggah, atau avatar inisial (SVG inline) bila belum ada.
     * Tidak memakai layanan eksternal (avatars.dicebear.com/api sudah tidak tersedia).
     */
    public function avatarUrl(): string
    {
        if (! empty($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }

        $words = preg_split('/\s+/u', trim((string) $this->name), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $initials = '';
        foreach (array_slice($words, 0, 2) as $w) {
            $initials .= mb_strtoupper(mb_substr($w, 0, 1));
        }
        $initials = $initials !== '' ? $initials : '?';

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 96 96">'
            . '<rect width="96" height="96" fill="#2a0f60"/>'
            . '<text x="48" y="48" fill="#ffffff" font-family="Arial,Helvetica,sans-serif" font-size="38" '
            . 'font-weight="600" text-anchor="middle" dominant-baseline="central">' . e($initials) . '</text></svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(RoleEnum::SuperAdmin);
    }
    public function isAdmin(): bool
    {
        return $this->hasRole(RoleEnum::Admin);
    }
    public function isModerator(): bool
    {
        return $this->hasRole(RoleEnum::Moderator);
    }
}   

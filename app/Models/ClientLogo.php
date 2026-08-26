<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class ClientLogo extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'nama_client',
        'logo',
        'website_url',
        'urutan',
        'is_active',
    ];
}

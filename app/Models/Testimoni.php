<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Testimoni extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'nama_client',
        'jabatan',
        'perusahaan',
        'rating',
        'testimoni',
        'foto',
        'urutan',
        'is_active',
    ];
}

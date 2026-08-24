<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Faq extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'question',
        'answer',
        'kategori',
        'urutan',
        'is_active',
    ];
}

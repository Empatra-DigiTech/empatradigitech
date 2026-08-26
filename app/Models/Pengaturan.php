<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_logo',
        'website_name',
        'website_maps',
        'website_motto',
        'website_phone',
        'website_address',
        'website_email',
        'stat_projects',
        'stat_clients',
        'stat_industries',
        'stat_years_experience',
    ];
}

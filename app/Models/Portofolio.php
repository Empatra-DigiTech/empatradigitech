<?php

namespace App\Models;

use App\Models\Concerns\DeletesStoredFiles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Te7aHoudini\LaravelTrix\Traits\HasTrixRichText;
use App\Traits\TrixRender;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use CyrildeWit\EloquentViewable\Contracts\Viewable;

class Portofolio extends Model implements Viewable
{
    use HasFactory, HasTrixRichText, TrixRender, Loggable, InteractsWithViews, DeletesStoredFiles;

    /** Kolom berkas yang dibersihkan dari storage saat diganti/dihapus (lihat DeletesStoredFiles). */
    protected array $storedFiles = ['image'];

    protected $fillable = [
        'title',
        'slug',
        'image',
        'date',
        'portofolio-trixFields',
        'creator',
        'klien',
        'industry',
        'layanan',
        'brand',
        'tantangan',
        'solusi',
        'fitur',
        'hasil',
        'demo_url',
    ];
}

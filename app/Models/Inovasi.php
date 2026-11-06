<?php

namespace App\Models;

use App\Models\Concerns\DeletesStoredFiles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Te7aHoudini\LaravelTrix\Traits\HasTrixRichText;
use App\Traits\TrixRender;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Inovasi extends Model
{
    use HasFactory, HasTrixRichText, TrixRender, Loggable, DeletesStoredFiles;

    /** Kolom berkas yang dibersihkan dari storage saat diganti/dihapus (lihat DeletesStoredFiles). */
    protected array $storedFiles = ['image'];
    
    protected $fillable = [
        'title',
        'slug',
        'image',
        'date',
        'kategori',
        'harga',
        'inovasi-trixFields',
    ];
}

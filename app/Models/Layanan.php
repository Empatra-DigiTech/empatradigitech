<?php

namespace App\Models;

use App\Models\Concerns\DeletesStoredFiles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Te7aHoudini\LaravelTrix\Traits\HasTrixRichText;
use App\Traits\TrixRender;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Layanan extends Model
{
    use HasFactory, HasTrixRichText, TrixRender, Loggable, DeletesStoredFiles;

    /** Kolom berkas yang dibersihkan dari storage saat diganti/dihapus (lihat DeletesStoredFiles). */
    protected array $storedFiles = ['image'];
    
    protected $fillable = [
        'title',
        'kategori',
        'description',
        'image',
        'layanan-trixFields',
    ];

    /**
     * Fixed category list used to group services on the public homepage.
     * Any service without a matching value falls under "Lainnya".
     */
    public static function kategoriOptions(): array
    {
        return [
            'Website',
            'Web Application',
            'Mobile Application',
            'Custom Software',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Te7aHoudini\LaravelTrix\Traits\HasTrixRichText;
use App\Traits\TrixRender;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Layanan extends Model
{
    use HasFactory, HasTrixRichText, TrixRender, Loggable;
    
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

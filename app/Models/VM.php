<?php

namespace App\Models;

use App\Models\Concerns\DeletesStoredFiles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Te7aHoudini\LaravelTrix\Traits\HasTrixRichText;
use App\Traits\TrixRender;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class VM extends Model
{
    use HasFactory, HasTrixRichText, TrixRender, Loggable, DeletesStoredFiles;

    /** Kolom berkas yang dibersihkan dari storage saat diganti/dihapus (lihat DeletesStoredFiles). */
    protected array $storedFiles = ['image'];

    protected $fillable = [
        'image',
        'visi',
        'misi',
    ];

    /**
     * Get misi items as array
     */
    public function getMisiArrayAttribute()
    {
        if (empty($this->misi)) {
            return [];
        }

        // Split by newline and filter empty lines
        $items = array_filter(
            explode("\n", $this->misi),
            function($item) {
                return !empty(trim($item));
            }
        );

        return array_values($items);
    }
}

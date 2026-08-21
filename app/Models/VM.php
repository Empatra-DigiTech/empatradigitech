<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Te7aHoudini\LaravelTrix\Traits\HasTrixRichText;
use App\Traits\TrixRender;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class VM extends Model
{
    use HasFactory, HasTrixRichText, TrixRender, Loggable;

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

<?php

namespace App\Models;

use App\Models\Concerns\DeletesStoredFiles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Kontak extends Model
{
    use HasFactory, Loggable, DeletesStoredFiles;

    /** Kolom berkas yang dibersihkan dari storage saat diganti/dihapus (lihat DeletesStoredFiles). */
    protected array $storedFiles = ['image'];

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'image',
    ];
}

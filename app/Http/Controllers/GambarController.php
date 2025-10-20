<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Models\DBPerhubungan;

class GambarController extends Controller
{
    public function show($id, $jenis)
    {
        $data = \App\Models\DBPerhubungan::where('idx', $id)->firstOrFail();
    
        $columnMap = [
            'depan' => 'fotodepansmall',
            'belakang' => 'fotobelakangsmall',
            'kanan' => 'fotokirismall',
            'kiri' => 'fotokirismall',
        ];
    
        if (!isset($columnMap[$jenis])) {
            abort(404, "Jenis gambar tidak dikenali.");
        }
    
        $imageData = $data->{$columnMap[$jenis]};
    
        return response($imageData)->header('Content-Type', 'image/png');
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataPengujian extends Model
{
    protected $table = 'datapengujian';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    public function rfid()
    {
        return $this->belongsTo(DBPerhubungan::class, 'idx', 'idx');
    }
    
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CabangSparepart extends Model
{
    public function sparepart(){
        return $this->belongsTo('App\Sparepart','kode_sparepart','kode')->with('kendaraan');
    }
    public function cabang(){
        return $this->belongsTo('App\Cabang');
    }
}

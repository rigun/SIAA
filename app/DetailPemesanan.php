<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailPemesanan extends Model
{
    public $timestamps = false;    
    public function sparepart(){
        return $this->hasOne('App\Sparepart','kode','kode_sparepart');
    }
    public function data(){
        return $this->hasOne('App\CabangSparepart','kode_sparepart','kode_sparepart')->with('sparepart');
    }
}

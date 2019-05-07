<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    public function kendaraan(){
        return $this->hasOne('App\Motor','id','id_kendaraan');
    }
    public function montir(){
        return $this->hasOne('App\Pegawai','id','id_montir');
    }
}

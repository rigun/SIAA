<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    public function supplier(){
        return $this->belongsTo('App\Supplier','id_supplier','id');
    }
    public function detail(){
        return $this->hasMany('App\DetailPemesanan','id_pemesanan','id')->with('data');
    }
    public function sales(){
        return $this->hasOne('App\Sales','id','id_sales');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    protected $primaryKey = 'kode';
    public $incrementing = false;
    public function kendaraan(){
        return $this->belongsToMany('App\Motor','sparepart_kendaraans','kode_sparepart','id_kendaraan');
    }
    public function supplier(){
        return $this->belongsTo('App\Supplier','id_supplier','id');
    }
}

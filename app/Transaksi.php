<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    public function cs(){
        return $this->hasOne('App\Pegawai','id','idCS');
    }
}

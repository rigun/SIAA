<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailSparepart extends Model
{
    public function sparepart(){
        return $this->hasOne('App\Sparepart','kode','kode_sparepart');
    }
}

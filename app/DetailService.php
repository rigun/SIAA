<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailService extends Model
{
    public function layanan(){
        return $this->hasOne('App\JasaService','id','id_service');
    }
}

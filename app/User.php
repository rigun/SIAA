<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'username', 'password'
    ];
    protected $hidden = [
        'password'
    ];
    public function role(){
        return $this->hasOne('App\Role','id','role_id');
    }
    public function detail(){
        return $this->belongsTo('App\Pegawai','id','user_id');
    }
    public function pegawai(){
        return $this->belongsTo('App\Pegawai','user_id','id');
    }
    public function getJWTIdentifier()
    {
      return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
      return [];
    }
}

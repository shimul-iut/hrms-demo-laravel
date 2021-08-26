<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['Name'];

    public function User(){
      return $this->hasMany('App\User');
    }

}

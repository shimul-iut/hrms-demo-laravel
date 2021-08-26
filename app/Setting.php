<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['employee_id', 'preferredNotification'];

    public function Employee(){
      return $this->belongsTo('App\Employee');
    }
}

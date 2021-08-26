<?php

namespace App;

use Employee;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['employee_id' , 'notificationText' , 'notificationStatus'];

    public function notificationInitiatar(){

      $this->belongsTo('App\Employee');
    }
}

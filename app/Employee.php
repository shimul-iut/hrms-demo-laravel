<?php

namespace App;

use User;
use Notification;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['user_id', 'FirstName', 'LastName' , 'PhoneNumber', 'ManagerID'];

    public function User()
    {
        return $this->belongsTo('App\User');
    }
    public function sendsNotification()
    {
        return $this->hasMany('App\Notification');
    }
    public function Setting()
    {
        return $this->hasOne('App\Setting');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeaveController extends Controller
{
  public function isThereAnyAvalilableLeave($user_id, $days)
  {
    //A Dummy checking system that will return true if the total number of available leaves
    //for the employee is more or equal than the requested total leaves. False if that's not the case
    return true;
  }

  public function adjustLeaveBalance($notification_id)
  {

    # A Dummy Leave Balance adjustment system after the leave request is approved;

  }


}

<?php

namespace App\Http\Controllers;

use Exception;
use DateTime;
use DateInterval;
use DatePeriod;
use App\User;
use App\Notification;
use App\Setting;
use App\Employee;
use App\Role;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\BroadcastNotificationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
  protected $leave;
  protected $broadcast;

  public function __construct(LeaveController $leave, BroadcastNotificationController $broadcast)
  {
    $this->leave = $leave;
    $this->broadcast = $broadcast;
    //$this->middleware('auth');
  }

  public function createLeaveNotification(Request $request)
  {

    $validator = $request->validate([
        'fromDate' => 'required|date|after:tomorrow',
        'toDate'  => 'required|date|after:fromDate',
    ]);
    // if ($validator->fails()) {
    //       return response()->json(["message" => $validator->errors()->all()], 400);
    // }
    $workingDays = $this->number_of_working_days($request->fromDate, $request->toDate);

    if($this->leave->isThereAnyAvalilableLeave($request->userID, $workingDays)){
      // A dummy check. Would be needed in real life scenerio
      try{

        $notification = new Notification();

        $notification->employee_id = $request->userID;
        $notification->notificationText = "Request Leave from ".$request->fromDate." To ".$request->toDate." (".$workingDays. " working days)";
        $notification->notificationStatus = "pending";

        $notification->save();

        //Get the Preferred Notification Channel for the Manager
        $manager_id = Employee::find($notification->employee_id)->ManagerID;
        $peferredChannel = Employee::find($manager_id)->Setting->preferredNotification;
        //send the broadcasting job to the injected class
        $this->broadcast->RouteNotifications($peferredChannel, $notification, 'created');
      }
      catch(Exception $e){
        return json_encode(array(
             'error' => array(
                'msg' => $e->getMessage(),
                'code' => $e->getCode(),
              )
            )
          );
      }
      return back();
    }
  }
  public function leaveApprove(Request $request)
  {
    //$notification = new Notification();
    foreach ($request->ids as $id) {
      $notification = Notification::find($id);
      $notification->update(['notificationStatus' => 'approved']);

      //Get the Preferred Notification Channel for the Employee
      $employee_id = $notification->employee_id;

      $peferredChannel = Employee::find($employee_id)->Setting->preferredNotification;
      //send the broadcasting job to the injected class
      $this->broadcast->RouteNotifications($peferredChannel, $notification);

      $this->leave->adjustLeaveBalance($id);

    }
    return $notification;
    //return $request->all();
  }

  public function leaveDecline(Request $request)
  {
    //$notification = new Notification();
    foreach ($request->ids as $id) {

      $notification = Notification::find($id);
      $notification->update(['notificationStatus' => 'declined']);

      //Get the Preferred Notification Channel for the Employee
      $employee_id = $notification->employee_id;

      $peferredChannel = Employee::find($employee_id)->Setting->preferredNotification;
      //send the broadcasting job to the injected class
      $this->broadcast->RouteNotifications($peferredChannel, $notification);

      $this->leave->adjustLeaveBalance($id);
    }
    return $notification;
  }

  public function updateEmployeeProfileNotification($employee_id){
      $allHRIDs = Role::where('Name' , 'HR Manager')->first()->User->pluck('id')->all();
      $manager_id = Employee::find($employee_id)->ManagerID;
      
  }

  public function number_of_working_days($from, $to)
  {
    $workingDays = [1, 2, 3, 4, 5]; # date format = N (1 = Monday, ...)
    $holidayDays = ['*-12-25', '*-01-01', '*-12-16', '*-02-21','*-03-26' , '*-08-15', '*-05-01']; # variable holidays, listed only the obvious one's

    $from = new DateTime($from);
    $to = new DateTime($to);
    $to->modify('+1 day');
    $interval = new DateInterval('P1D');
    $periods = new DatePeriod($from, $interval, $to);

    $days = 0;
    foreach ($periods as $period) {
        if (!in_array($period->format('N'), $workingDays)) continue;
        if (in_array($period->format('Y-m-d'), $holidayDays)) continue;
        if (in_array($period->format('*-m-d'), $holidayDays)) continue;
        $days++;
    }
    return $days;
  }
}

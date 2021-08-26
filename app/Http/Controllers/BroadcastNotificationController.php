<?php

namespace App\Http\Controllers;

use App\Employee;
use App\User;
use Illuminate\Http\Request;
use Nexmo\Laravel\Facade\Nexmo;
use App\Jobs\SendEmail;
//use Nexmo\Laravel\NexmoServiceProvider;

Class BroadcastNotificationController extends Controller
{

  public function RouteNotifications($preference, $notification){

    $employee_id = Employee::find($notification->employee_id);
    $name = $employee_id->FirstName.' '.$employee_id->LastName;

    //Find Employee Phone and email
    $phone = $employee_id->PhoneNumber;
    $email = $employee_id->User->email;
    $userID = $notification->employee_id;
    if($notification->notificationStatus === 'pending')
    {
      $subText = " Submitted For ";
      //When the status is Pending, initiator is the Employee. So we need the Managers phone and email at this stage
      $phone = Employee::find($employee_id->ManagerID)->PhoneNumber;
      $email = User::find($employee_id->ManagerID)->email;
      $userID = User::find($employee_id->ManagerID)->id;
    }
    elseif ($notification->notificationStatus == 'approved') $subText = " Approved For ";
    else $subText = " Rejected For ";

    $message = $notification->notificationText.$subText.$name;

    //Assign methods according to the user preferences

    if($preference === "SMS") $this->sendSMS($notification, $message, $phone);
    elseif($preference === "Email") $this->sendEmail($notification, $message, $email);
    else $this->sendWebPush($notification, $message, $userID);
    
  }

  public function sendSMS($notification, $message, $phone){

    //Using the Nexmo Library from Vonage for Demo Purpose
    Nexmo::message()->send([
        'to' => '8801877689607', //Ideally should have been $phone
        'from' => '8801819458461',
        'text' => $message
      ]);
      //echo "Message Sent";
      $notification->update(['allNotified' => 1]);
      return back();
  }

  public function sendEmail($notification, $message, $email)
  {
    $details = ['email' => 'shimulcit08@gmail.com', 'message' => $message]; //ideally it would have been $email
    SendEmail::dispatch($details); // Using Mailgun Free account

    $notification->update(['allNotified' => 1]);
    return back();
  }
  public function sendWebPush($message, $id )
  {
    $firebaseToken = User::where('id', $id )->whereNotNull('device_token')->pluck('device_token')->all();

    $SERVER_API_KEY = 'AAAAkRcVwRc:APA91bEdWquUvCQk8FMtSGlwOf0PkU8myrT6gHmN36seKcG8Le4kdNBJye5eAoMrOcnFoo9M6AxhXCHAbs7Ov7z-rfQY9CrU7It5UDEuCP3CKxE1TYpg57WFUmuh409FHJkJYa210jPU';

    $data = [
        "registration_ids" => $firebaseToken,
        "notification" => [
        "title" => 'New Leave Notification',
        "body" => $request->body,
         ]
    ];
    $dataString = json_encode($data);

    $headers = [
        'Authorization: key=' . $SERVER_API_KEY,
        'Content-Type: application/json',
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

    $response = curl_exec($ch);

    $notification->update(['allNotified' => 1]);
     }
}

<?php

namespace App\Http\Controllers;

use Exception;
use App\User;
use App\Role;
use App\Employee;
use App\Setting;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
  protected $notify;

  public function __construct(NotificationController $notify){
      $this->notify = $notify;
  }
    public function randomUserName($alphabet) {
      $pass = array(); //remember to declare $pass as an array
      $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
      for ($i = 0; $i < 5; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
      }
      return implode($pass); //turn the array into a string
    }

    public function editEmployeeProfileView(Request $request){

        $user = User::where('id', $request->user()->id)->with('Employee')->get();
        $preference = Setting::where('employee_id', $request->user()->id)->first()->preferredNotification;
        $options = ['SMS', 'Email', 'Web'];
        return view('auth.profile', compact('user', 'options', 'preference'));
    }

    public function createEmployee(Request $request){
      $username = $request->Lname.$this->randomUserName($request->Fname.$request->phone);

      $validator = $request->validate([
          'email' => 'required|unique:users,email',
          'phone' => 'required|numeric|digits:11',
          'employee_role' => 'required|between:1,3',
          'manager_selector' => 'required',
      ]);

      try{

          $user = new User();
          //First Create User
          $user->username = $username; //An unique user name will be assigned by HR Manager
          $user->password = bcrypt('12345678'); // A default password is assigned to all to create the account. Users will change it after first login
          $user->email = $request->email;
          $user->role_id=$request->employee_role;

          $user->save();

          $employee = new Employee();
          // Now Create the Employee Record
          $employee->user_id = $user->id;
          $employee->FirstName = $request->Fname;
          $employee->LastName = $request->Lname;
          $employee->PhoneNumber = $request->phone;
          $employee->ManagerID = $request->manager_selector;

          $employee->save();

          $setting = new Setting();
          // Finally Create the notification setting preference, SMS by default
          $setting->employee_id = $employee->id;

          $setting->save();

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

    public function updateEmployee(Request $request){

      User::find($request->employee_id)->update(['email' => $request->email]);
      Employee::find($request->employee_id)->update(['FirstName' => $request->Fname, 'LastName' => $request->Lname,  'PhoneNumber' => $request->phone]);
      Setting::where('employee_id', $request->employee_id)->update(['preferredNotification' => $request->notification]);
      
      $this->notify->updateEmployeeProfileNotification($request->employee_id);

      return back();


      //dd($request->all());
    }
}

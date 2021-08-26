<?php


use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();

Route::get('/notification', function () {
    return view('notification-tester');
});

Route::get('/', function(){
    return view('welcome');
});
Route::get('/profile', 'EmployeeController@editEmployeeProfileView');

Route::get('/profile-create', 'HomeController@createProfileView');

Route::get('/leave-request', function(){

      return view('auth.leave-request');
});
Route::get('/leave-all', function(){

      return view('auth.all-notifications');
});


Route::post('/save-token', 'HomeController@saveToken')->name('save-token');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/test-notification', function () {
    event(new App\Events\notifyUpdate('Someone', 'Needs  a Break'));
    return "Event has been sent!";
});

Route::get('/sms', 'BroadCastNotificationController@sendSMS');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/send-mail', function () {

    $details = [
        'title' => 'Mail from ItSolutionStuff.com',
        'body' => 'This is for testing email using smtp'
    ];

    \Mail::to('shimulcit08@gmail.com')
    ->cc(['eth0.netstat@gmail.com'])
    ->send(new \App\Mail\NotificationMail($details));

    dd("Email is Sent.");
});

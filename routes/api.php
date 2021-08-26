<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:web')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::middleware('web')->group(function(){

  Route::prefix('v1')->group(function () {

    Route::post('/profile/create', 'EmployeeController@createEmployee')->name('create-new-profile');

    Route::post('/profile/udpate', 'EmployeeController@updateEmployee')->name('update-profile');

    Route::post('/leave/create', 'NotificationController@createLeaveNotification')->name('create-leave');

    Route::post('/leave/approve', 'NotificationController@leaveApprove');

    Route::post('/leave/decline', 'NotificationController@leaveDecline');

  //});
});

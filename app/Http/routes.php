<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');


});
Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'auth'
], function() {
    Route::post('logout', 'AuthController@logout2');
    Route::get('user', 'AuthController@user2');
});
Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'security'
], function () {
    Route::get('breaches', 'HaveIBeenPwnd@breaches');
    Route::get('password', 'HaveIBeenPwnd@password');
    Route::post('password', 'HaveIBeenPwnd@password');

});
Route::group([
    'prefix' => 'user'
], function() {
    Route::post('addpassword', 'UserController@AddUserPassword');
    Route::post('updatepassword', 'UserController@UpdateUserPassword');
    Route::get('getpasswords', 'UserController@GetPasswords');
});
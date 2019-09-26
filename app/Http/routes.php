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

/*Route::get('/', function () {
    return view('welcome');
});*/
Route::any('/', function(){return "Welcome to ".env('APP_NAME');});
/*****************Default Route*************** */

//User Login
Route::post('api/login','api\Users@login');
Route::post('api/registration','api\Users@registration');
Route::post('api/forgot-password','api\Users@forgot_password');
Route::any('reset-password/{token}','api\Users@userResetPassword');
Route::any('user-verification/{user_id}','api\Users@userVerification');
Route::any('user-reset-password-process','api\Users@userResetPasswordProcess');

/**************API Route******************* */
Route::any('welcome-to-api','Api@index');
Route::group(['prefix' => 'api','middleware' => 'jwt.auth'], function () {
	//Users
	Route::post('logout','api\Users@logout');
	Route::post('update-password','api\Users@update_password');
	Route::post('update-profile','api\Users@updateProfile');
	Route::post('contact-list','api\Users@contactList');
	Route::post('add-task','api\Users@addTask');
	
	Route::post('subscription-add','api\Users@subscriptionAdd');
	Route::post('deactive-student','api\Users@deactiveStudent');
});


/**************API Route******************* */
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
/************************Admin routes************************* */


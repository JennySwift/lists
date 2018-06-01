<?php

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

//Auth::routes();

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/logout', 'Auth\LoginController@logout');

//// Registration Routes...
//Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//Route::post('register', 'Auth\RegisterController@register');
//
//// Password Reset Routes...
//Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
//Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
//Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
//Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('/', ['middleware' => 'auth', function () {
    return view('pages.home');
}]);
//Route::get('/', function () {
//    return view('pages.home');
//});

//Route::get('/items', function () {
//    return 'hi';
//});

Route::group(['namespace' => 'API', 'prefix' => 'api', 'middleware' => ['auth', 'web']], function () {
    //Cors routes
//    header('Access-Control-Allow-Origin: http://budget_app.dev:8000');
//    Route::group(['middleware' => ['cors']], function () {
//        Route::resource('items', 'ItemsController', ['except' => ['create', 'edit']]);
//    });

    //This didn't work without the id specified for the show method
//    Route::resource('users', 'UsersController', ['only' => ['show']]);

    Route::post('pushNotifications', 'PushNotificationsController@sendPushNotification');
});

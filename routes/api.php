<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['namespace' => 'API', 'middleware' => 'auth'], function () {
    //Cors routes
//    header('Access-Control-Allow-Origin: http://budget_app.dev:8000');
//    Route::group(['middleware' => ['cors']], function () {
//        Route::resource('items', 'ItemsController', ['except' => ['create', 'edit']]);
//    });

    Route::resource('items', 'ItemsController', ['except' => ['create', 'edit']]);
    Route::resource('categories', 'CategoriesController', ['except' => ['create', 'edit']]);
    Route::resource('feedback', 'FeedbackController', ['only' => ['store']]);
    //This didn't work without the id specified for the show method
//    Route::resource('users', 'UsersController', ['only' => ['show']]);

    Route::delete('items/emptyTrash', 'ItemsController@emptyTrash');
    Route::put('items/undoDelete', 'ItemsController@undoDeleteItem');
    Route::get('users', 'UsersController@show');
    Route::post('pushNotifications', 'PushNotificationsController@sendPushNotification');
});

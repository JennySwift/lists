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

Route::group(['namespace' => 'API', 'middleware' => 'auth:api'], function () {
    //This needs to be before the resource is created or the empty trash route doesn't work
    Route::delete('items/emptyTrash', 'ItemsController@emptyTrash');
    Route::put('/items/restore/{id}', 'ItemsController@restore');
    Route::resource('items', 'ItemsController', ['except' => ['create', 'edit']]);

    Route::resource('categories', 'CategoriesController', ['except' => ['create', 'edit']]);
    Route::resource('feedback', 'FeedbackController', ['only' => ['store']]);

    Route::put('/users', 'UsersController@update');
//    Route::resource('users', 'UsersController', ['only' => ['update']]);

    Route::put('items/undoDelete', 'ItemsController@undoDeleteItem');
    Route::get('users', 'UsersController@show');
});

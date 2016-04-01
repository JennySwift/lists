<?php

require app_path('Http/Routes/auth.php');

Route::get('/', ['middleware' => 'auth', function () {
    return view('pages.home');
}]);
//Route::get('/', function () {
//    return view('pages.home');
//});

//Route::get('/items', function () {
//    return 'hi';
//});


// API
Route::group(['namespace' => 'API', 'prefix' => 'api'], function () {

    //Cors routes
//    header('Access-Control-Allow-Origin: http://budget_app.dev:8000');
    Route::group(['middleware' => ['cors']], function () {
        Route::resource('items', 'ItemsController', ['except' => ['create', 'edit']]);
    });

//    Auth routes
    Route::group(['middleware' => 'auth'], function () {
        Route::resource('categories', 'CategoriesController', ['except' => ['create', 'edit']]);
        Route::resource('feedback', 'FeedbackController', ['only' => ['store']]);
        //This didn't work without the id specified for the show method
//    Route::resource('users', 'UsersController', ['only' => ['show']]);

        Route::delete('items/emptyTrash', 'ItemsController@emptyTrash');
        Route::put('items/undoDelete', 'ItemsController@undoDeleteItem');
        Route::get('users', 'UsersController@show');
        Route::post('pushNotifications', 'PushNotificationsController@sendPushNotification');
    });


});


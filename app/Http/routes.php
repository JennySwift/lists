<?php

/**
 * Views
 */

use App\Item;

Route::get('/', 'Lists\ListsController@index');

//Credits
Route::get('/credits', function()
{
    return view('credits');
});

Route::get('/test', function()
{
    $item = Item::find(13);
    //dd($);
    return $item->breadcrumb()->lists('id');
});

//ng-includes

/**
 * @VP:
 * How would I return a file in the templates directory instead of the views directory?
 */
Route::get('/ItemTemplate', function()
{
    return view('ItemTemplate');
});

/**
 * Laravel 5.1 Authentication
 * Not sure if I'm supposed to use these.
 * They are from the 5.1 docs but it seems to work without them.
 */

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
//Route::get('auth/register', 'Auth\AuthController@getRegister');
//Route::post('auth/register', 'Auth\AuthController@postRegister');

/**
 * Authentication
 */

//Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function(){
//
//    Route::group(['middleware' => 'guest'], function(){
//        // Login
//        Route::get('login', ['as' => 'auth.login', 'uses' => 'AuthController@getLogin']);
//        Route::post('login', ['as' => 'auth.login.store', 'before' => 'throttle:2,60', 'uses' => 'AuthController@postLogin']);
//
//        // Register
//        Route::get('register', ['as' => 'auth.register', 'uses' => 'AuthController@getRegister']);
//        Route::post('register', ['as' => 'auth.register.store', 'uses' => 'AuthController@postRegister']);
//    });
//
//    Route::group(['middleware' => 'auth'], function(){
//        // Logout
//        Route::get('logout', ['as' => 'auth.logout', 'uses' => 'AuthController@getLogout']);
//    });
//
//});

Route::controllers([
    // 'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

/**
 * Bindings
 */

/**
 * Resources
 */

Route::resource('items', 'Lists\ListsController', ['only' => ['show']]);

/**
 * Ajax
 */

Route::post('filter', 'Lists\ListsController@filter');



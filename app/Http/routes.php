<?php

require app_path('Http/Routes/auth.php');

Route::get('/', 'API\ItemsController@pageLoad');

Route::put('undoDeleteItem', 'API\ItemsController@undoDeleteItem');
Route::delete('items/emptyTrash', 'API\ItemsController@emptyTrash');

// API
Route::group(['namespace' => 'API', 'prefix' => 'api', 'middleware' => 'auth'], function () {
    Route::resource('items', 'ItemsController', ['except' => ['create', 'edit']]);
    Route::resource('categories', 'CategoriesController', ['except' => ['create', 'edit']]);
});


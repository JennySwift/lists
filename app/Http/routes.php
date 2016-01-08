<?php

require app_path('Http/Routes/auth.php');

Route::get('/', ['middleware' => 'auth', function () {
    return view('pages.items.items-page');
}]);


// API
Route::group(['namespace' => 'API', 'prefix' => 'api', 'middleware' => 'auth'], function () {
    Route::resource('items', 'ItemsController', ['except' => ['create', 'edit']]);
    Route::resource('categories', 'CategoriesController', ['except' => ['create', 'edit']]);

    Route::delete('items/emptyTrash', 'ItemsController@emptyTrash');
    Route::put('items/undoDelete', 'ItemsController@undoDeleteItem');
});


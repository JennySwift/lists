<?php

require app_path('Http/Routes/auth.php');
require app_path('Http/Routes/pages.php');

//ng-includes
Route::get('/ItemTemplate', function()
{
    return view('pages/items/item/item');
});

//angular directive templates
Route::get('/sortable', function()
{
    return view('directives/sortable');
});

Route::put('undoDeleteItem', 'API\ItemsController@undoDeleteItem');
Route::delete('items/emptyTrash', 'API\ItemsController@emptyTrash');

// API
Route::group(['namespace' => 'API', 'prefix' => 'api', 'middleware' => 'auth'], function () {
    Route::resource('items', 'ItemsController', ['except' => ['create', 'edit']]);
    Route::resource('categories', 'CategoriesController', ['except' => ['create', 'edit']]);
});


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

Route::post('filter', 'API\ItemsController@filter');
Route::post('updateItem', 'API\ItemsController@updateItem');
Route::put('undoDeleteItem', 'API\ItemsController@undoDeleteItem');

// API
Route::group(['namespace' => 'API', 'prefix' => 'api'], function () {
    Route::resource('items', 'ItemsController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::resource('categories', 'CategoriesController', ['only' => ['show', 'store']]);
});


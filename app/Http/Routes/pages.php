<?php

Route::get('/', 'API\ItemsController@pageLoad');
//Route::get('/categories', 'API\CategoriesController@pageLoad');

//Credits
Route::get('/credits', function()
{
return view('credits');
});
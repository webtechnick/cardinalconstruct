<?php

Route::get('/', 'PagesController@home');
Route::get('/remodel', 'PagesController@remodel');
Route::get('/marvin', 'PagesController@marvin');
Route::get('/simonton', 'PagesController@simonton');
Route::get('/contact', 'PagesController@contact');

Route::resource('gallery', 'GalleriesController');
Route::get('gallery/{slug}', 'GalleriesController@show');
Route::post('gallery/{slug}/photos', [
	'as' => 'store_photo_path',
	'uses' => 'GalleriesController@addPhoto'
]);

Route::auth();

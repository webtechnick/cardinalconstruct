<?php
/**
 * Basic Pages
 */
Route::get('/', 'PagesController@home');
Route::get('/remodel', 'PagesController@remodel');
Route::get('/marvin', 'PagesController@marvin');
Route::get('/simonton', 'PagesController@simonton');
Route::get('/contact', 'PagesController@contact');

/**
 * Auth
 */
Route::auth();

/**
 * Gallery
 */
Route::get('gallery', 'GalleriesController@index');
Route::get('gallery/create', 'GalleriesController@create');
Route::get('gallery/{slug}', 'GalleriesController@show');
Route::post('gallery', 'GalleriesController@store');

/**
 * Photos
 */
Route::post('gallery/{slug}/photos', [
	'as' => 'store_photo_path',
	'uses' => 'PhotosController@store'
]);
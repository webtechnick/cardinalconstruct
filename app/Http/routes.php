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
Route::delete('gallery/{slug}', ['as' => 'gallery.destroy', 'uses' => 'GalleriesController@destroy']);
Route::get('gallery/{slug}/edit', ['as' => 'gallery.edit', 'uses' => 'GalleriesController@edit']);
Route::get('gallery/{slug}', 'GalleriesController@show');
Route::patch('gallery/{slug}', ['as' => 'gallery.update', 'uses' => 'GalleriesController@update']);
Route::post('gallery', 'GalleriesController@store');



/**
 * Photos
 */
Route::post('gallery/{slug}/photos', [
	'as' => 'store_photo_path',
	'uses' => 'PhotosController@store'
]);
Route::delete('photos/{photo}', 'PhotosController@destroy');
Route::get('photos/{photo}/toggle', 'PhotosController@toggle');
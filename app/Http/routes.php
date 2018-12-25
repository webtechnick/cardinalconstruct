<?php
/**
 * Basic Pages
 */
Route::get('/', ['as' => 'home', 'uses' => 'PagesController@home']);
Route::get('/remodel', 'PagesController@remodel')->name('remodel');
Route::get('/marvin', 'PagesController@marvin')->name('marvin');
Route::get('/simonton', 'PagesController@simonton')->name('simonton');
Route::get('/contact', 'PagesController@contact')->name('contact');
Route::post('/contact', 'PagesController@send');

/**
 * Auth
 */
Route::auth();

/**
 * Gallery
 */
Route::get('gallery', ['as' => 'gallery.index', 'uses' => 'GalleriesController@index']);
Route::get('gallery/create', ['as' => 'gallery.create', 'uses' => 'GalleriesController@create']);
Route::delete('gallery/{slug}', ['as' => 'gallery.destroy', 'uses' => 'GalleriesController@destroy']);
Route::get('gallery/{slug}/edit', ['as' => 'gallery.edit', 'uses' => 'GalleriesController@edit']);
Route::get('gallery/{slug}', ['as' => 'gallery.show', 'uses' => 'GalleriesController@show']);
Route::patch('gallery/{slug}', ['as' => 'gallery.update', 'uses' => 'GalleriesController@update']);
Route::post('gallery', ['as' => 'gallery.store', 'uses' => 'GalleriesController@store']);



/**
 * Photos
 */
Route::post('gallery/{slug}/photos', [
	'as' => 'photos.store',
	'uses' => 'PhotosController@store'
]);
Route::delete('photos/{photo}', ['as' => 'photos.delete', 'uses' => 'PhotosController@destroy']);
Route::get('photos/{photo}/toggle', ['as' => 'photos.delete', 'uses' => 'PhotosController@toggle']);
Route::get('photos/{photo}/edit', ['as' => 'photos.delete', 'uses' => 'PhotosController@edit']);
Route::patch('photos/{photo}', ['as' => 'photos.delete', 'uses' => 'PhotosController@update']);
<?php
Route::group([
    'middleware' => ['auth', 'admin'],
    'as' => 'admin.',
    'prefix' => 'admin',
    'namespace' => 'Admin',
], function() {
    // Users
    Route::get('/', 'UsersController@index')->name('users.index');
    Route::get('/users/create', 'UsersController@create')->name('users.create');
    Route::post('/users', 'UsersController@store')->name('users.store');
    Route::get('/users/edit/{user}', 'UsersController@edit')->name('users.edit');
    Route::patch('/users/{user}', 'UsersController@update')->name('users.update');
    Route::get('/users/delete/{user}', 'UsersController@destroy')->name('users.delete');
    // Reviews
    Route::get('/reviews', 'ReviewsController@index')->name('reviews.index');
    Route::get('/reviews/edit/{review}', 'ReviewsController@edit')->name('reviews.edit');
    Route::patch('/reviews/{review}', 'ReviewsController@update')->name('reviews.update');
    Route::get('/reviews/approve/{review}', 'ReviewsController@approve')->name('reviews.approve');
    Route::get('/reviews/deny/{review}', 'ReviewsController@deny')->name('reviews.deny');
    Route::get('/reviews/delete/{review}', 'ReviewsController@destroy')->name('reviews.delete');
});


/**
 * Basic Pages
 */
Route::get('/', ['as' => 'home', 'uses' => 'PagesController@home']);
Route::get('/simonton', 'PagesController@simonton')->name('simonton');
Route::get('/dycwindows', 'PagesController@dycwindows')->name('dycwindows');
Route::get('/contact', 'PagesController@contact')->name('contact');
Route::get('/about', 'PagesController@about')->name('about');
Route::post('/contact', 'PagesController@send')->name('quote');


// Aut
Auth::routes();

// Gallery
Route::get('gallery', ['as' => 'gallery.index', 'uses' => 'GalleriesController@index']);
Route::get('gallery/create', ['as' => 'gallery.create', 'uses' => 'GalleriesController@create']);
Route::delete('gallery/{slug}', ['as' => 'gallery.destroy', 'uses' => 'GalleriesController@destroy']);
Route::get('gallery/{slug}/edit', ['as' => 'gallery.edit', 'uses' => 'GalleriesController@edit']);
Route::get('gallery/{slug}', ['as' => 'gallery.show', 'uses' => 'GalleriesController@show']);
Route::patch('gallery/{slug}', ['as' => 'gallery.update', 'uses' => 'GalleriesController@update']);
Route::post('gallery', ['as' => 'gallery.store', 'uses' => 'GalleriesController@store']);


// Reviews
Route::get('reviews', 'ReviewsController@index')->name('reviews.index');
Route::get('/reviews/create', 'ReviewsController@create')->name('reviews.create');
Route::post('/reviews', 'ReviewsController@store')->name('reviews.store');



/**
 * Photos
 */
Route::post('gallery/{slug}/photos', [
    'as' => 'photos.store',
    'uses' => 'PhotosController@store'
]);
Route::delete('photos/{photo}', ['as' => 'photos.delete', 'uses' => 'PhotosController@destroy']);
Route::get('photos/{photo}/toggle', ['as' => 'photos.toggle', 'uses' => 'PhotosController@toggle']);
Route::get('photos/{photo}/edit', ['as' => 'photos.edit', 'uses' => 'PhotosController@edit']);
Route::patch('photos/{photo}', ['as' => 'photos.update', 'uses' => 'PhotosController@update']);
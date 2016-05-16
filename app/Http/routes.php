<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'PagesController@home');
Route::get('/remodel', 'PagesController@remodel');
Route::get('/marvin', 'PagesController@marvin');
Route::get('/simonton', 'PagesController@simonton');
Route::get('/contact', 'PagesController@contact');

Route::resource('gallery', 'GalleriesController');
Route::get('gallery/{slug}', 'GalleriesController@show');
Route::post('gallery/{slug}/photos', 'GalleriesController@addPhoto');

Route::auth();

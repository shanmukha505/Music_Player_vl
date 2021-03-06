<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/', function () {

    return view('welcome');
});



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/home', 'HomeController@store');

Route::post('/upload', 'SongController@store');

Route::post('/isLoggedIn','SongController@isLoggedIn');

Route::post('/song','SongController@get');

Route::get('/songFile/{place}/{file}','SongController@getFile');

Route::post('/songsrc','SongController@src');

Route::post('/play', 'PlaylistController@store');


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

Route::get('/poll/view/{id}', function () {
    return view('poll-view');
});

Route::get('/poll/success', function () {
    return view('poll-success');
});

Route::get('/poll/email-view', function () {
    return view('poll-email');
});

Route::get('/poll/not-found', function () {
    return view('poll-not-found');
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

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


Route::post('/shortenUrl', 'Controller@shortenUrl')->name('shortenUrl');

Route::get('/{code}', 'Controller@toUrl')->name('toUrl');

<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', 'HomeController@welcome')->name('welcome');
Auth::routes();

/* CUSTOMER ROUTES */
Route::group(['middleware' => ['auth', 'role:customer']], function () {
    Route::get('/home', 'HomeController@index')->name('home');
});


/* ADMIN ROUTES */
Route::group(['middleware' => ['auth', 'role:admin'], 'prefix' => 'admin'], function () {
    Route::get('/home', 'HomeController@admin')->name('admin.home');
});

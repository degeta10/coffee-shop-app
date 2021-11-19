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

    /* ACCOUNT ROUTES */
    Route::get('/account/profile', 'AccountController@profile')->name('account.profile');
    Route::patch('/account/profile/update', 'AccountController@updateProfile')->name('account.profile.update');
    Route::get('/account/wallet', 'AccountController@wallet')->name('account.wallet');
    Route::patch('/account/wallet/update', 'AccountController@updateWallet')->name('account.wallet.update');

    /* ORDER ROUTES */
    Route::get('/orders', 'CustomerOrderController@index')->name('orders');
    Route::post('/orders/search', 'CustomerOrderController@search')->name('orders.search');
    Route::get('/orders/create', 'CustomerOrderController@create')->name('orders.create');
    Route::post('/orders/create', 'CustomerOrderController@store')->name('orders.store');
    Route::post('/orders/{order}/cancel', 'CustomerOrderController@cancel')->name('orders.cancel');
});


/* ADMIN ROUTES */
Route::group(['middleware' => ['auth', 'role:admin'], 'prefix' => 'admin'], function () {
    Route::get('/home', 'HomeController@admin')->name('admin.home');
});

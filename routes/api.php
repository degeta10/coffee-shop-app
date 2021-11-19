<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* AUTHENTICATION ROUTES */

Route::post('login', 'Api\AuthController@login');
Route::post('signup', 'Api\AuthController@signup');

/* USER ROUTES */
Route::group([
    'middleware' => ['auth:sanctum', 'role:customer'],
    'prefix' => 'auth'
], function () {
    Route::get('profile', 'Api\AuthController@profile');
    Route::post('logout', 'Api\AuthController@logout');
    Route::post('profile', 'Api\AuthController@updateProfile');
});

/* CUSTOMER ORDER ROUTES */
Route::group([
    'middleware' => ['auth:sanctum', 'role:customer'],
], function () {
    Route::apiResource('order', 'Api\CustomerOrderController');
    Route::post('order/{order}', 'Api\CustomerOrderController@cancel');
});

/* CUSTOMER WALLET ROUTES */
Route::group([
    'middleware' => ['auth:sanctum', 'role:customer'],
], function () {
    Route::get('wallet', 'Api\CustomerWaletController@getBalance');
    Route::post('wallet/update', 'Api\CustomerWaletController@update');
});

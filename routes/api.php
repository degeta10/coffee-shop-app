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

Route::post('login', 'Api\AuthController@login');
Route::post('signup', 'Api\AuthController@signup');

Route::group([
    'middleware' => ['auth:sanctum', 'role:customer'],
], function () {
    // Route::post('logout', 'Api\AuthController@logout');
    // Route::post('me', 'Api\AuthController@me');
});

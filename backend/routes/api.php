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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::group([
//     'middleware' => 'cors'
// ], function () {
//     Route::post('/register', 'UserController@register');
//     Route::post('/login', 'UserController@login');
//     Route::post('/auth/refresh', 'UserController@refresh');
//     Route::get('/check-alive', 'APIController@check_alive');
// });

Route::post('/register', 'UserController@register');
Route::post('/login', 'UserController@login');
Route::post('/auth/refresh', 'UserController@refresh');
Route::get('/check-alive', 'APIController@check_alive');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/check-passport', 'UserController@check_passport');
    Route::post('/logout', 'UserController@logout');
});

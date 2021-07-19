<?php

use Illuminate\Http\Request;

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
Route::middleware(['log.route'])->group(function () {
    Route::post('/user/register','AuthController@register');
    Route::get('/user/refresh', 'AuthController@refresh');
    Route::post('/user/login','AuthController@login');
});


Route::group(['middleware' => ['auth.jwt', 'log.route']], function() {
    Route::get('auth/user', 'AuthController@user');
    Route::get('info/user', 'AuthController@userInfo');
    Route::get('/user/logout', 'AuthController@logout');
    Route::get('/messages/{id}', 'MessageController@index');
    Route::post('/messages/send', 'MessageController@send');
    Route::get('/read/{id}', 'MessageController@read');
	Route::get('/contacts', 'ContactController@index');
  });

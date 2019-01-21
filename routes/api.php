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
//api endpoints for client 
Route::apiResources(['client' => 'API\ClientController']);
//api endpoints for user
Route::apiResources(['user' => 'API\UserController']);
//get user profile
Route::get('profile','API\UserController@profile');
//update user profile
Route::put('profile','API\UserController@updateProfile');

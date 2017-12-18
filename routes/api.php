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

// sample API
// Route::get('/rooms',function(){
// 	return \App\Room::all();
// });

Route::post('login', 'AuthController@login');

// Route::group(['middleware' => ['jwt.auth']], function() {
//     Route::get('logout', 'AuthController@logout');
//     Route::get('test', function(){
//         return response()->json(['foo'=>'bar']);
//     });
// });

// Route::post('login', 'Auth\LoginController@login');
//
// Route::group([
//     'prefix' => 'restricted',
//     'middleware' => 'auth:api',
// ], function () {
//
//     // Authentication Routes...
//     Route::get('logout', 'Auth\LoginController@logout');
//
//     Route::get('/test', function () {
//         return 'authenticated';
//     });
// });

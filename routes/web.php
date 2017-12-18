<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/



Route::get('/', function () {
    return view('home');
});

Route::prefix('auth')->group(function () {
  Route::post('login', 'AuthController@login');
  Route::post('user/createaccount', 'AuthController@register')->name('user.create');
  Route::get('user/verify/{activationCode}', 'AuthController@verifyUser');
});

// Route::get('user/verify/{activationCode}', 'Auth/RegisterController@verifyUser');

Route::prefix('api')->group(function () {
  Route::post('/bridge/add', 'BridgeController@store');
  Route::get('/bridge/all', 'BridgeController@showAll');
  Route::get('/bridge/{id}', 'BridgeController@show');
  Route::get('/bridge/{id}/allInspections', 'InspectionController@showAllforBridge');
  Route::post('/bridge/{id}/edit', 'BridgeController@update');
  Route::post('/inspection/photos/upload', 'ImageController@store');
  Route::post('/inspection/add', 'InspectionController@store');
  Route::post('/inspection/{id}/edit', 'InspectionController@update');
  Route::get('/inspection/{id}/getAllItems', 'InspectionController@show');
  Route::post('/inspection/{id}/delete', 'InspectionController@destroy');
  Route::get('/inspection/{id}/getImageItems', 'ImageController@show');
  Route::get('/inspection/{inspectionId}/{imageId}/getPhoto', 'ImageController@showSpecificPhoto');
  Route::post('/inspection/{id}/deleteImage', 'ImageController@destroyImage');
  Route::get('/inspection/{id}', 'InspectionController@show');
  Route::post('/inspection/{inspectionId}/{imageId}/addMarker', 'ImageMarkerController@store');
  Route::get('/inspection/{id}/getMarker', 'ImageMarkerController@showMarker');
  Route::get('/inspection/{inspectionId}/{imageId}/showMarkers', 'ImageMarkerController@showMarkers');
  Route::post('/inspection/{inspectionId}/{imageId}/{markerId}/updateMarker', 'ImageMarkerController@update');
  Route::post('/inspection/{id}/deleteMarker', 'ImageMarkerController@delete');
  // Route::get('bridge', 'PortfolioController@createPortfolio')->name('bridge');
  // Route::get('bridge/add', 'PortfolioController@createPortfolioAdd')->name('bridge.add');
  // Route::post('bridge/add', 'PortfolioController@store');
  // Route::get('/bridge/edit/{id}', 'PortfolioController@edit')->name('bridge.edit');
  // Route::post('/bridge/update/{id}', 'PortfolioController@update')->name('bridge.update');
  // Route::get('/portfolio/edit/{id}/uploadPreviewImage', 'PortfolioController@uploadPreviewImage');
  //
  // Route::get('category', 'CategoryController@createCategory')->name('category');
  // Route::get('category/add', 'CategoryController@createCategoryAdd')->name('category.add');
  // Route::post('category/add', 'CategoryController@store');
  // Route::get('/category/edit/{id}', 'CategoryController@edit')->name('category.edit');
  // Route::post('/category/update/{id}', 'CategoryController@update')->name('category.update');
});

// Route::get('/', 'NewsletterController@create')->name('home');

Route::get('/{vue?}', function () { return view('home'); })->where('vue', '[\/\w\.-]*');

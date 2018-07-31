<?php



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


Route::group([
    'prefix' => 'v1'
], function () {
    Route::get('download-image', 'PostController@downloadImageFormPost');
    Route::post('add-image', 'ImageController@addImageFromFolder');
    Route::post('attach-image-post', 'ImageController@attachImagePost');
    Route::post('login', 'Auth\AuthController@login');
    Route::post('refresh', 'Auth\AuthController@refresh');
    Route::group(['middleware' => 'auth'], function () {
        Route::post('me', 'Auth\AuthController@me');
        Route::post('logout', 'Auth\AuthController@logout');
        // Route::apiResource('users', 'UserController');
        // Route::apiResource('categories', 'CategoryController');
        // Route::apiResource('posts', 'PostController');
        // Route::apiResource('images', 'ImageController');
    });

    Route::apiResource('categories', 'CategoryController');
    Route::apiResource('posts', 'PostController');
    Route::apiResource('menus', 'MenuController');

});






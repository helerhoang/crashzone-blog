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
    Route::post('login', 'Auth\AuthController@login');
    Route::post('refresh', 'Auth\AuthController@refresh');
    Route::group(['middleware' => 'auth'], function () {
        Route::post('me', 'Auth\AuthController@me');
        Route::post('logout', 'Auth\AuthController@logout');
    });
    Route::apiResource('users', 'UserController');

    Route::apiResource('posts', 'PostController');
    Route::apiResource('menus', 'MenuController');
    Route::apiResource('images', 'ImageController');
    /*
     * CATEGORY
     */
    Route::apiResource('categories', 'CategoryController');
    Route::get('categories-deleted', 'CategoryController@indexDeleted')->name('categories.indexDeleted');
    Route::delete('categories-deleted/{id}', 'CategoryController@destroyDeleted')->name('categories.destroyDeleted');
    Route::put('categories-deleted/{id}', 'CategoryController@restoreDeleted')->name('categories.restoreDeleted');

});






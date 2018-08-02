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
    Route::get('download-image', 'PostController@downloadImageFormPost')->name('backup.download-image');
    Route::post('add-image', 'ImageController@addImageFromFolder')->name('backup.add-image');
    Route::post('attach-image-post', 'ImageController@attachImagePost')->name('backup.attach-image-post');
    Route::post('login', 'Auth\AuthController@login')->name('auth.login');
    Route::post('refresh', 'Auth\AuthController@refresh')->name('auth.refresh');
    Route::group(['middleware' => 'auth'], function () {
        Route::post('me', 'Auth\AuthController@me')->name('auth.me');
        Route::post('logout', 'Auth\AuthController@logout')->name('auth.logout');
    });
    Route::apiResource('users', 'UserController');


    Route::apiResource('menus', 'MenuController');
    Route::apiResource('images', 'ImageController');
    /*
     *  CATEGORY
     */
    Route::apiResource('categories', 'CategoryController');
    Route::get('categories-deleted', 'CategoryController@indexDeleted')->name('categories.indexDeleted');
    Route::delete('categories-deleted/{id}', 'CategoryController@destroyDeleted')->name('categories.destroyDeleted');
    Route::put('categories-deleted/{id}', 'CategoryController@restoreDeleted')->name('categories.restoreDeleted');
    /*
     *  POSTS
     */
    Route::apiResource('posts', 'PostController');
    Route::get('posts-deleted', 'PostController@indexDeleted')->name('posts.indexDeleted');
    Route::delete('posts-deleted/{id}', 'PostController@destroyDeleted')->name('posts.destroyDeleted');
    Route::put('posts-deleted/{id}', 'PostController@restoreDeleted')->name('posts.restoreDeleted');

});






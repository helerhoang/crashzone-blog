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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::group([
//     'prefix' => 'v1'
// ], function () {
//     Route::post('login', 'Auth\AuthController@login')->name('login');
//     Route::group([
//         'middleware' => 'auth'
//     ], function () {
//         Route::post('me', 'Auth\AuthController@me');
//         Route::post('logout', 'Auth\AuthController@logout');
//         Route::post('refresh', 'Auth\AuthController@refresh');


//         /**
//          * USERS CONTROLLER
//          */
//         Route::get('users', 'UsersController@index');
//         Route::delete('users/{id}', 'UsersController@destroy')->where(['id' => '[0-9]+']);
//         // Route::resource('users', 'UsersController');
//     });
// });

Route::group([
    'prefix' => 'v1'
], function () {
    Route::post('login', 'Auth\AuthController@login');
    Route::post('refresh', 'Auth\AuthController@refresh');
    Route::group(['middleware' => 'auth'], function () {
        Route::post('me', 'Auth\AuthController@me');
        Route::post('logout', 'Auth\AuthController@logout');
        Route::resource('user', 'UserController');
    });
});




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

Route::namespace('Api')->middleware('api')->group(function () {

    Route::post('test', 'TestController@test')->name('test');

    Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function () {

        Route::namespace('Store')->prefix('store')->name('store.')->group(function () {
            Route::resource('auth', 'AuthController', ['only' => ['index']]);
            Route::resource('page', 'PageController', ['only' => ['index']]);
        });

        Route::post('login', 'Auth\LoginController@login')->name('login');

        Route::middleware('auth:admin')->group(function () {
            Route::get('logout', 'Auth\LoginController@logout')->name('logout');

            Route::resource('/dashboard', 'DashboardController', ['only' => ['index']]);

            Route::resource('category-types', 'CategoryTypesController', ['except' => ['create', 'edit']]);
            Route::put('{slug}/categories/sort', 'CategoriesController@sort')->name('categories.sort');
            Route::resource('{slug}/categories', 'CategoriesController', ['except' => ['create', 'edit']]);

            Route::put('post-types/sort', 'PostTypesController@sort')->name('post-types.sort');
            Route::resource('post-types', 'PostTypesController', ['except' => ['create', 'edit']]);

            Route::put('{slug}/sort', 'PostController@sort')->name('posts.sort');
            Route::resource('{slug}/posts', 'PostController', ['except' => ['create', 'edit']]);

            Route::resource('users', 'AdminController', ['except' => ['create', 'edit']]);
        });
    });

    Route::get('{slug}', 'PostController@index')->name('index');
    Route::get('{slug}/{postSlug}', 'PostController@show')->name('show');

    Route::group(['middleware' => ['auth:user']], function () {
    });
});

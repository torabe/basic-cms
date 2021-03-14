<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/**
 * 管理画面
 *
 * APIのURL以外のリクエストに対してはadmin.indexテンプレートを返す
 * 画面遷移はフロントエンドのvue-routerが制御する
 */
Route::namespace('Admin')->name('admin.')->group(function () {
    Auth::routes();
    Route::get('admin/{any?}', 'HomeController@index')->where('any', '.+')->name('index');
});

Route::group([], function () {
    Auth::routes(['verify' => true]);

    Route::get('/home', 'HomeController@index')->name('home');

    Route::group(['middleware' => ['auth:user', 'verified']], function () {
    });
});

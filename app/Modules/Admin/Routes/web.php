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

Route::prefix('admin')->group(function() {
//    Route::get('/', 'AdminController@index');
    //后台管理路由
    Route::get('/', function(){
        return view('admin');
    });

    Route::prefix('auth')->group(function() {
        Route::any('login', 'AuthController@login');
        Route::any('me', 'AuthController@me');
        Route::any('logout', 'AuthController@logout');
    });

    Route::prefix('banners')->group(function() {
        Route::any('/', 'System\BannerController@index');
        Route::any('/create', 'System\BannerController@create');
        Route::any('/update', 'System\BannerController@update');
    });
});

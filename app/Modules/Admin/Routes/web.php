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
        Route::any('/delete', 'System\BannerController@delete');
    });

    Route::prefix('configs')->group(function() {
        Route::any('/', 'System\ConfigController@index');
        Route::any('/create', 'System\ConfigController@create');
        Route::any('/update', 'System\ConfigController@update');
        Route::any('/delete', 'System\ConfigController@delete');
    });

    Route::prefix('friendlinks')->group(function() {
        Route::any('/', 'System\FriendlinkController@index');
        Route::any('/create', 'System\FriendlinkController@create');
        Route::any('/update', 'System\FriendlinkController@update');
        Route::any('/delete', 'System\FriendlinkController@delete');
    });

    Route::prefix('protocols')->group(function() {
        Route::any('/', 'System\ProtocolController@index');
        Route::any('/create', 'System\ProtocolController@create');
        Route::any('/update', 'System\ProtocolController@update');
        Route::any('/delete', 'System\ProtocolController@delete');
    });

    Route::prefix('versions')->group(function() {
        Route::any('/', 'System\VersionController@index');
        Route::any('/create', 'System\VersionController@create');
        Route::any('/update', 'System\VersionController@update');
        Route::any('/delete', 'System\VersionController@delete');
    });

    Route::prefix('admins')->group(function() {
        Route::any('/', 'Rabc\AdminController@index');
        Route::any('/create', 'Rabc\AdminController@create');
        Route::any('/update', 'Rabc\AdminController@update');
        Route::any('/delete', 'Rabc\AdminController@delete');
    });

    Route::prefix('roles')->group(function() {
        Route::any('/', 'Rabc\AdminRoleController@index');
        Route::any('/create', 'Rabc\AdminRoleController@create');
        Route::any('/update', 'Rabc\AdminRoleController@update');
        Route::any('/delete', 'Rabc\AdminRoleController@delete');
    });

    Route::prefix('menus')->group(function() {
        Route::any('/', 'Rabc\AdminMenuController@index');
        Route::any('/create', 'Rabc\AdminMenuController@create');
        Route::any('/update', 'Rabc\AdminMenuController@update');
        Route::any('/delete', 'Rabc\AdminMenuController@delete');
    });

    Route::prefix('adminlogs')->group(function() {
        Route::any('/', 'Log\AdminLogController@index');
        Route::any('/create', 'System\AdminLogController@create');
        Route::any('/update', 'System\AdminLogController@update');
        Route::any('/delete', 'System\AdminLogController@delete');
    });

    Route::prefix('adminloginlogs')->group(function() {
        Route::any('/', 'Log\AdminLoginLogController@index');
        Route::any('/create', 'System\AdminLoginLogController@create');
        Route::any('/update', 'System\AdminLoginLogController@update');
        Route::any('/delete', 'System\AdminLoginLogController@delete');
    });
});

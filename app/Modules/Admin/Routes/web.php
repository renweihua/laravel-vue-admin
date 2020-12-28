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

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Http\Middleware\CheckAuth;
use App\Modules\Admin\Http\Middleware\AdminLog;

Route::prefix('admin')->group(function() {
//    Route::get('/', 'AdminController@index');
    //后台管理路由
    Route::get('/', function(){
        return view('admin::admin');
    });

    Route::prefix('auth')->group(function() {
        Route::any('login', 'AuthController@login');
        Route::any('me', 'AuthController@me')->middleware(CheckAuth::class);
        Route::any('logout', 'AuthController@logout')->middleware(CheckAuth::class);
        Route::any('getRabcList', 'AuthController@getRabcList')->middleware(CheckAuth::class);
    });

    Route::middleware([CheckAuth::class, AdminLog::class])->group(function () {

        // 首页
        Route::get('indexs', 'IndexController@index');
        // 月份表列表
        Route::get('get_month_lists', 'IndexController@getMonthList');

        // 文件上传
        Route::post('upload_file', 'UploadController@file');

        Route::prefix('banners')->group(function() {
            Route::get('/', 'System\BannerController@index');
            Route::post('/', 'System\BannerController@create');
            Route::put('/', 'System\BannerController@update');
            Route::delete('/', 'System\BannerController@delete');
        });

        // 配置管理
        Route::prefix('configs')->group(function() {
            Route::get('/', 'System\ConfigController@index');
            Route::post('/', 'System\ConfigController@create');
            Route::put('/', 'System\ConfigController@update');
            Route::delete('/', 'System\ConfigController@delete');
        });

        // 友情链接
        Route::prefix('friendlinks')->group(function() {
            Route::get('/', 'System\FriendlinkController@index');
            Route::post('/', 'System\FriendlinkController@create');
            Route::put('/', 'System\FriendlinkController@update');
            Route::delete('/', 'System\FriendlinkController@delete');
        });

        Route::prefix('protocols')->group(function() {
            Route::get('/', 'System\ProtocolController@index');
            Route::post('/', 'System\ProtocolController@create');
            Route::put('/', 'System\ProtocolController@update');
            Route::delete('/', 'System\ProtocolController@delete');
        });

        Route::prefix('versions')->group(function() {
            Route::get('/', 'System\VersionController@index');
            Route::post('/', 'System\VersionController@create');
            Route::put('/', 'System\VersionController@update');
            Route::delete('/', 'System\VersionController@delete');
        });

        Route::prefix('admins')->group(function() {
            Route::get('/', 'Rabc\AdminController@index');
            Route::post('/', 'Rabc\AdminController@create');
            Route::put('/', 'Rabc\AdminController@update');
            Route::delete('/', 'Rabc\AdminController@delete');
            Route::get('/getSelectLists', 'Rabc\AdminController@getSelectLists');
        });

        Route::prefix('admin_roles')->group(function() {
            Route::get('/', 'Rabc\AdminRoleController@index');
            Route::post('/', 'Rabc\AdminRoleController@create');
            Route::put('/', 'Rabc\AdminRoleController@update');
            Route::delete('/', 'Rabc\AdminRoleController@delete');
            Route::get('/getSelectLists', 'Rabc\AdminRoleController@getSelectLists');
        });

        Route::prefix('admin_menus')->group(function() {
            Route::get('/', 'Rabc\AdminMenuController@index');
            Route::post('/', 'Rabc\AdminMenuController@create');
            Route::put('/', 'Rabc\AdminMenuController@update');
            Route::delete('/', 'Rabc\AdminMenuController@delete');
            Route::get('/getSelectLists', 'Rabc\AdminMenuController@getSelectLists');
        });

        // 管理员日志
        Route::prefix('adminlogs')->group(function() {
            Route::get('/', 'Log\AdminLogController@index');
            Route::delete('/', 'System\AdminLogController@delete');
        });

        // 管理员登录日志
        Route::prefix('adminloginlogs')->group(function() {
            Route::get('/', 'Log\AdminLoginLogController@index');
            Route::delete('/', 'System\AdminLoginLogController@delete');
        });
    });
});

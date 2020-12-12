<?php

use Illuminate\Support\Facades\Route;

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


//后台管理路由
Route::get('admin', function(){
	return view('admin');
});

Route::post('vab-mock-server/login',function(){
    return response()->json([
        'code'=>200,
        'msg'=>'success',
        'data'=>[
            'accessToken'=>'admin'
        ]
    ]);
});


Route::post('vab-mock-server/userInfo',function(){
    return response()->json([
        'code'=>200,
        'msg'=>'success',
        'data'=>[
            'avatar' => 'https://i.gtimg.cn/club/item/face/img/8/15918_100.gif',
            'permissions'=>['admin'],
            'username'=>'admin',
        ]
    ]);
});


Route::post('login',function(){
    return response()->json([
        'code'=>200,
        'msg'=>'success',
        'data'=>[
            'accessToken'=>'admin'
        ]
    ]);
});

Route::post('user/info',function(){
    return response()->json([
        'code'=>200,
        'msg'=>'success',
        'data'=>[
            'permissions'=>['admin'],
            'userName'=>'admin',
        ]
    ]);
});

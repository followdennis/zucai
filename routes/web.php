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

Route::get('spider',['uses'=>'TestController@index']);
Route::get('aoke',['uses'=>'TestController@aoke']);

Route::get('test',['uses'=>'TestController@test']);

Route::get('score',['uses'=>'TestController@score']);
//前台路由
Route::namespace('Frontend')->group(function(){
    Route::get('/',['uses'=>'IndexController@index']);
    Route::get('index',['uses'=>'IndexController@index']);
    Route::post('calculate',['uses'=>'IndexController@calculate']);//数据统计
    Route::get('betting',['uses'=>'IndexController@betting']);
    Route::get('betting_save',['uses'=>'IndexController@betting_save']);
    Route::get('order',['uses'=>'OrderController@index']);
    Route::get('/order/detail',['uses'=>'OrderController@detail']);//订单列表查看比赛详情
    Route::get('/order/remark',['uses'=>'OrderController@remark']);//备注信息展示
    Route::get('/match/judge',['uses'=>'IndexController@judege']);//分析数据
    Route::post('/order/remark_save',['uses'=>'OrderController@remarkSave']);//保存第二次备注
});


Route::get('number',['uses'=>'TestController@getNumber']);

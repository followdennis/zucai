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

//前台路由
Route::namespace('Frontend')->group(function(){
    Route::get('/',['uses'=>'IndexController@index']);
    Route::get('index',['uses'=>'IndexController@index']);
    Route::post('calculate',['uses'=>'IndexController@calculate']);//数据统计
    Route::get('betting',['uses'=>'IndexController@betting']);
});


Route::get('number',['uses'=>'TestController@getNumber']);

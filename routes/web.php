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

Route::get('/',['uses'=>'Frontend\IndexController@index']);
Route::get('index',['uses'=>'Frontend\IndexController@index']);
Route::post('calculate',['uses'=>'Frontend\IndexController@calculate']);

Route::get('number',['uses'=>'TestController@getNumber']);

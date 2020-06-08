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
//后台登陆
Route::get('public/login','Admin\PublicController@login')->name('login');
Route::post('public/login','Admin\PublicController@login')->name('login');
Route::middleware(['login'])->group(function () {
    //后台首页
    Route::get('index/index','Admin\IndexController@index')->name('index');
    Route::get('index/welcome','Admin\IndexController@welcome')->name('welcome');
    //后台用户
    Route::get('admin/index','Admin\AdminController@index')->name('admin_index');
});

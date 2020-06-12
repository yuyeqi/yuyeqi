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
    Route::get('admin/lists','Admin\AdminController@getAdminLists')->name('admin_lists');
    Route::get('admin/addShow','Admin\AdminController@addShow')->name('admin_add_show');
    Route::post('admin/add','Admin\AdminController@add')->name('admin_add');
    Route::get('admin/showInfo/{id}','Admin\AdminController@showInfo')->name('admin_show');
    Route::get('admin/edit/{id}','Admin\AdminController@edit')->name('admin_edit_show');
    Route::post('admin/editPost/{id}','Admin\AdminController@editPost')->scene('edit');;
    Route::get('admin/delete/{id}','Admin\AdminController@delete')->name('admin_delete');
    Route::post('admin/updatePwd/{id}','Admin\AdminController@updatePwd')->name('admin_update_pwd');
});

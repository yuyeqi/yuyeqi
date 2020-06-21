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
//后台用户
Route::prefix('public')->group(function (){
    Route::get('login','Admin\PublicController@login')->name('login');
    Route::post('login','Admin\PublicController@login')->name('login');
    Route::get('loginOut','Admin\PublicController@loginOut')->name('loginOut');
    Route::post('upload','Admin\PublicController@upload')->name('upload');
});
Route::group(['prefix'=>'hp', 'namespace'=>'Admin'],function () {
    //后台首页
    Route::get('index/index','Admin\IndexController@index')->name('index');
    Route::get('index/welcome','Admin\IndexController@welcome')->name('welcome');
    //后台用户
    Route::prefix('admin')->group(function (){
        Route::get('index','Admin\AdminController@index')->name('admin_index');
        Route::get('lists','Admin\AdminController@getAdminLists')->name('admin_lists');
        Route::get('addShow','Admin\AdminController@addShow')->name('admin_add_show');
        Route::post('add','Admin\AdminController@add')->name('admin_add');
        Route::get('showInfo/{id}','Admin\AdminController@showInfo')->name('admin_show');
        Route::get('edit/{id}','Admin\AdminController@edit')->name('admin_edit_show');
        Route::post('editPost/{id}','Admin\AdminController@editPost')->scene('edit');;
        Route::get('delete/{id}','Admin\AdminController@delete')->name('admin_delete');
        Route::post('updatePwd','Admin\AdminController@updatePwd')->name('admin_update_pwd');
        Route::post('updateStatus','Admin\AdminController@updateStatus')->name('admin_update_status');
        Route::post('deleteAll','Admin\AdminController@deleteAll')->name('admin_delete_all');
    });
    //商品管理
    Route::prefix('goods')->group(function (){
        Route::get('index','Admin\GoodsController@index')->name('goods_index');
        Route::get('getGoodsLists','Admin\GoodsController@getGoodsLists')->name('goods_lists');
        Route::get('detail/{id}','Admin\GoodsController@detail')->name('goods_detail')->where('id', '[0-9]+');
        Route::get('addShow','Admin\GoodsController@addShow')->name('goods_add_show');
        Route::post('add','Admin\GoodsController@add')->name('goods_add');
    });

});

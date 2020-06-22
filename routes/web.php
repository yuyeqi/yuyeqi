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
Route::group(['prefix'=>'hp', 'namespace'=>'Admin','middleware'=>'login'],function () {
    //后台首页
    Route::get('index/index','IndexController@index')->name('index');
    Route::get('index/welcome','IndexController@welcome')->name('welcome');
    //后台用户
    Route::prefix('admin')->group(function (){
        Route::get('index','AdminController@index')->name('admin_index');
        Route::get('lists','AdminController@getAdminLists')->name('admin_lists');
        Route::get('addShow','AdminController@addShow')->name('admin_add_show');
        Route::post('add','AdminController@add')->name('admin_add');
        Route::get('showInfo/{id}','AdminController@showInfo')->name('admin_show');
        Route::get('edit/{id}','AdminController@edit')->name('admin_edit_show');
        Route::post('editPost/{id}','AdminController@editPost')->scene('edit');;
        Route::get('delete/{id}','AdminController@delete')->name('admin_delete');
        Route::post('updatePwd','AdminController@updatePwd')->name('admin_update_pwd');
        Route::post('updateStatus','AdminController@updateStatus')->name('admin_update_status');
        Route::post('deleteAll','AdminController@deleteAll')->name('admin_delete_all');
    });
    //商品管理
    Route::prefix('goods')->group(function (){
        Route::get('index','GoodsController@index')->name('goods_index');
        Route::get('getGoodsLists','GoodsController@getGoodsLists')->name('goods_lists');
        Route::get('detail/{id}','GoodsController@detail')->name('goods_detail')->where('id', '[0-9]+');
        Route::get('addShow','GoodsController@addShow')->name('goods_add_show');
        Route::post('add','GoodsController@add')->name('goods_add');
    });

});

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
        Route::get('edit/{id}','GoodsController@edit')->name('editShow')->where('id', '[0-9]+');
        Route::get('detail/{id}','GoodsController@detail')->name('goods_detail')->where('id', '[0-9]+');
        Route::get('addShow','GoodsController@addShow')->name('goods_add_show');
        Route::post('add','GoodsController@add')->name('goods_add');
        Route::post('delBatch','GoodsController@delBatch')->name('goods_delete_all');
    });
    //新闻管理
    Route::prefix('news')->group(function (){
        Route::get('index','NewsController@index')->name('news_index');
        Route::get('getNewsLists','NewsController@getNewsLists')->name('news_lists');
        Route::get('addShow','NewsController@addShow')->name('news_add_show');
        Route::post('add','NewsController@add')->name('news_add');
        Route::get('editShow/{id}','NewsController@editShow')->name('news_edit_show')->where('id', '[0-9]+');
        Route::post('edit','NewsController@edit')->name('news_edit');
        Route::post('delBatch','NewsController@delBatch')->name('news_del');
        Route::post('updateStatus','NewsController@updateStatus')->name('news_update_status');
        Route::post('updateRecommend','NewsController@updateRecommend')->name('news_update_recommend');
    });

    //案例管理
    Route::prefix('cases')->group(function (){
        Route::get('index','CasesController@index')->name('cases_index');
        Route::get('getCasesLists','CasesController@getNewsLists')->name('cases_lists');
        Route::get('addShow','CasesController@addShow')->name('cases_add_show');
        Route::post('add','CasesController@add')->name('cases_add');
        Route::get('editShow/{id}','CasesController@editShow')->name('cases_edit_show')->where('id', '[0-9]+');
        Route::post('edit','CasesController@edit')->name('cases_edit');
        Route::post('delBatch','CasesController@delBatch')->name('cases_del');
        Route::post('updateStatus','CasesController@updateStatus')->name('cases_update_status');
    });
    //私人定制管理
    Route::prefix('person')->group(function (){
        Route::get('index','personController@index')->name('person_index');
        Route::get('getPersonCateLists','personController@getPersonLists')->name('person_lists');
        Route::get('editShow/{id}','personController@editShow')->name('person_edit_show')->where('id', '[0-9]+');
        Route::post('edit','personController@edit')->name('person_edit');
        Route::get('auditShow/{id}','personController@auditShow');
        Route::post('delBatch','personController@delBatch')->name('person_del');
        Route::post('updateStatus','personController@updateStatus')->name('person_update_status');
    });
    //私人定制分类管理
    Route::prefix('personCate')->group(function (){
        Route::get('index','PersonCateController@index')->name('personCate_index');
        Route::get('getPersonCateLists','PersonCateController@getPersonCateLists')->name('personCate_lists');
        Route::get('addShow','PersonCateController@addShow')->name('personCate_add_show');
        Route::post('add','PersonCateController@add')->name('personCate_add');
        Route::get('editShow/{id}','PersonCateController@editShow')->name('personCate_edit_show')->where('id', '[0-9]+');
        Route::post('edit','PersonCateController@edit')->name('personCate_edit');
        Route::post('delBatch','PersonCateController@delBatch')->name('personCate_del');
        Route::post('updateStatus','PersonCateController@updateStatus')->name('personCate_update_status');
    });
    //轮播图管理
    Route::prefix('slideshow')->group(function (){
        Route::get('index','SlideshowController@index')->name('slideshow_index');
        Route::get('getSlideshowLists','SlideshowController@getSlideshowLists')->name('slideshow_lists');
        Route::get('addShow','SlideshowController@addShow')->name('slideshow_add_show');
        Route::post('add','SlideshowController@add')->name('slideshow_add');
        Route::get('editShow/{id}','SlideshowController@editShow')->name('slideshow_edit_show')->where('id', '[0-9]+');
        Route::post('edit','SlideshowController@edit')->name('slideshow_edit');
        Route::post('delBatch','SlideshowController@delBatch')->name('slideshow_del');
        Route::post('updateStatus','SlideshowController@updateStatus')->name('slideshow_update_status');
    });
    //轮播图管理
    Route::prefix('book')->group(function (){
        Route::get('index','BookController@index')->name('book_index');
        Route::get('getBookAdminLists','BookController@getBookAdminLists')->name('book_lists');
        Route::get('editShow/{id}','BookController@editShow')->name('book_edit_show')->where('id', '[0-9]+');
        Route::post('edit','BookController@edit')->name('book_edit');
        Route::post('delBatch','BookController@delBatch')->name('book_del');
        Route::post('updateStatus','BookController@updateStatus')->name('book_update_status');
    });
    //商品分离管理
    Route::prefix('goodsCate')->group(function (){
        Route::get('index','GoodsCateController@index')->name('goodsCate_index');
        Route::get('getLists','GoodsCateController@getLists')->name('goodsCate_lists');
        Route::get('addShow','GoodsCateController@addShow')->name('goodsCate_add_show');
        Route::post('add','GoodsCateController@add')->name('goodsCate_add');
        Route::get('editShow/{id}','GoodsCateController@editShow')->name('gooodsCate_edit_show')->where('id', '[0-9]+');
        Route::post('edit','GoodsCateController@edit')->name('goodsCate_edit');
        Route::post('delBatch','GoodsCateController@delBatch')->name('gooodsCate_del');
        Route::post('updateStatus','GoodsCateController@updateStatus')->name('gooodsCate_update_status');
    });
});

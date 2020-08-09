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
    Route::any('uploadAdmin','Admin\PublicController@uploadAdmin')->name('upload');
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
    //后台用户
    Route::prefix('config')->group(function (){
        Route::get('index','ConfigController@index')->name('config_index');
        Route::get('lists','ConfigController@getConfigLists')->name('config_lists');
        Route::get('editShow/{configNo}','ConfigController@editShow');
        Route::post('edit','ConfigController@edit')->name('config_edit');;
    });
    //商品管理
    Route::prefix('goods')->group(function (){
        Route::get('index','GoodsController@index')->name('goods_index');
        Route::get('getGoodsLists','GoodsController@getGoodsLists')->name('goods_lists');
        Route::get('edit/{id}','GoodsController@edit')->name('editShow')->where('id', '[0-9]+');
        Route::get('detail/{id}','GoodsController@detail')->name('goods_detail')->where('id', '[0-9]+');
        Route::get('addShow','GoodsController@addShow')->name('goods_add_show');
        Route::post('add','GoodsController@add')->name('goods_add');
        Route::post('updateGoods','GoodsController@updateGoods')->name('goods_edit');
        Route::post('delBatch','GoodsController@delBatch')->name('goods_delete_all');
        Route::get('comment','GoodsController@comment')->name('goods_comment');
        Route::get('getCommentLists','GoodsController@getCommentLists')->name('goods_comment_lists');
        Route::post('updateStatus','GoodsController@updateStatus')->name('goods_update_status');
        Route::post('updateTop','GoodsController@updateTop')->name('goods_update_top');
        Route::post('delBatchCommnet','GoodsController@delBatchCommnet')->name('goods_delete_comment');
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
        Route::get('index','PersonController@index')->name('person_index');
        Route::get('getPersonCateLists','PersonController@getPersonLists')->name('person_lists');
        Route::get('editShow/{id}','PersonController@editShow')->name('person_edit_show')->where('id', '[0-9]+');
        Route::post('edit','PersonController@edit')->name('person_edit');
        Route::get('auditShow/{id}','PersonController@auditShow');
        Route::post('delBatch','PersonController@delBatch')->name('person_del');
        Route::post('updateStatus','PersonController@updateStatus')->name('person_update_status');
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
        Route::get('audit/{id}','BookController@audit')->name('book_audit_show')->where('id', '[0-9]+');
    });
    //商品分类管理
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
    //用户分类
    Route::prefix('userCate')->group(function (){
        Route::get('index','UserCateController@index')->name('userCate_index');
        Route::get('getLists','UserCateController@getLists')->name('userCate_lists');
        Route::get('editShow/{id}','UserCateController@editShow')->name('userCate_edit_show')->where('id', '[0-9]+');
        Route::get('addShow','UserCateController@addShow')->name('userCate_add_show');
        Route::post('add','UserCateController@add')->name('userCate_add');
        Route::post('edit','UserCateController@edit')->name('userCate_edit');
        Route::post('delBatch','UserCateController@delBatch')->name('userCate_del');
        Route::post('updateStatus','UserCateController@updateStatus')->name('userCate_update_status');
    });

    //用户
    Route::prefix('user')->group(function (){
        Route::get('index','UserController@index')->name('user_index');
        Route::get('getLists','UserController@getLists')->name('user_lists');
        Route::get('editShow/{id}','UserController@editShow')->name('user_edit_show')->where('id', '[0-9]+');
        Route::get('auditShow/{id}','UserController@auditShow')->name('user_audit_show');
        Route::post('audit/','UserController@audit')->name('user_audit');
        Route::get('show/{id}','UserController@show')->name('user_show');
        Route::get('account/{id}','UserController@account')->name('user_show');
        Route::post('edit','UserController@edit')->name('user_edit');
        Route::post('delBatch','UserController@delBatch')->name('user_del');
        Route::post('updateStatus','UserController@updateStatus')->name('user_update_status');
        Route::get('withdraw','UserController@withdraw')->name('user_withdraw');
        Route::get('withdrawList','UserController@withdrawList')->name('user_withdraw_lists');
        Route::get('walletDeal','UserController@walletDeal')->name('user_walletDeal');
        Route::get('walletDealList','UserController@walletDealList')->name('user_walletDeal_lists');
        Route::get('scoreDeal','UserController@scoreDeal')->name('user_scoreDeal');
        Route::get('scoreDealList','UserController@scoreDealList')->name('user_scoreDeal_lists');
        Route::get('promoter','UserController@promoter')->name('user_promoter');
        Route::get('promoterlList','UserController@promoterlList')->name('user_promoterl_lists');
        Route::post('delBatchWithdraw','UserController@delBatchWithdraw')->name('user_withdraw_del');
        Route::post('delBatchWallet','UserController@delBatchWallet')->name('user_wallet_del');
        Route::post('delBatchScore','UserController@delBatchScore')->name('user_score_del');
        Route::post('delBatchPromoter','UserController@delBatchPromoter')->name('user_promoter_del');
    });
    //订单
    Route::prefix('order')->group(function (){
        Route::get('index','OrderController@index')->name('order_index');
        Route::get('getLists','OrderController@getLists')->name('order_list');
        Route::get('editShow/{id}','OrderController@editShow')->name('order_edit_show')->where('id', '[0-9]+');
        Route::get('show/{id}','OrderController@show')->name('order_show');
        Route::post('edit','OrderController@edit')->name('order_edit');
        Route::post('delBatch','OrderController@delBatch')->name('order_del');
    });
    //兑换商品分类管理
    Route::prefix('exchangeCate')->group(function (){
        Route::get('index','ExchangeCateController@index')->name('exchangeCate_index');
        Route::get('getLists','ExchangeCateController@getLists')->name('exchangeCate_lists');
        Route::get('addShow','ExchangeCateController@addShow')->name('exchangeCate_add_show');
        Route::post('add','ExchangeCateController@add')->name('exchangeCate_add');
        Route::get('editShow/{id}','ExchangeCateController@editShow')->name('exchangeCate_edit_show')->where('id', '[0-9]+');
        Route::post('edit','ExchangeCateController@edit')->name('exchangeCate_edit');
        Route::post('delBatch','ExchangeCateController@delBatch')->name('exchangeCate_del');
        Route::post('updateStatus','ExchangeCateController@updateStatus')->name('exchangeCate_update_status');
    });
    //商品管理
    Route::prefix('exchange')->group(function (){
        Route::get('index','ExchangeController@index')->name('exchange_index');
        Route::get('getLists','ExchangeController@getLists')->name('exchange_lists');
        Route::get('edit/{id}','ExchangeController@edit')->name('editShow')->where('id', '[0-9]+');
        Route::get('detail/{id}','ExchangeController@detail')->name('exchange_detail')->where('id', '[0-9]+');
        Route::get('addShow','ExchangeController@addShow')->name('exchange_add_show');
        Route::post('add','ExchangeController@add')->name('exchange_add');
        Route::post('updateGoods','ExchangeController@updateGoods')->name('exchange_edit');
        Route::post('delBatch','ExchangeController@delBatch')->name('exchange_delete_all');
        Route::get('record','ExchangeController@record')->name('exchange_record');
        Route::get('getRecordList','ExchangeController@getRecordList')->name('exchange_record_lists');
        Route::get('auditShow/{id}','ExchangeController@auditShow')->name('exchange_auditShow');
        Route::post('audit','ExchangeController@audit')->name('exchange_audit');
        Route::post('delBatchRecord','ExchangeController@delBatchRecord')->name('exchange_delete_record');
    });
});

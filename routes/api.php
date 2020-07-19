<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//不需要登陆
//公共路由
Route::prefix('index')->group(function (){
    Route::get('slideshow','V1\IndexController@getSlideShowLists');
    Route::get('getUserInfo','V1\IndexController@getUserInfo');
    Route::get('getNewsGoods','V1\IndexControllerid@getNewsGoods');
});
//微信
Route::prefix('wechat')->group(function (){
    Route::get('slideshow','V1\IndexController@getSlideShowLists');
    Route::get('getUserInfo','V1\IndexController@getUserInfo');
    Route::get('getNewsGoods','V1\IndexControllerid@getNewsGoods');
});
//需要登陆
Route::group(['prefix'=>'v1', 'namespace'=>'Api'],function (){
    Route::get('/wx/wxLogin/{code}',"V1\PublicController@wxLogin");
    //首页
    Route::prefix('index')->group(function (){
        Route::get('slideshow','V1\IndexController@getSlideShowLists');
        Route::get('getUserInfo','V1\IndexController@getUserInfo');
        Route::get('getNewsGoods','V1\IndexControllerid@getNewsGoods');
        Route::get('getNewsLists','V1\IndexController@getNewsLists');
        Route::get('getCaseLists','V1\IndexController@getCaseLists');
        Route::get('getCasesDetail/{id}','V1\IndexController@getCasesDetail');
    });
    //新闻
    Route::prefix('news')->group(function (){
        Route::get('getNewsPageLists','V1\NewsController@getNewsPageLists');
        Route::get('getNewsDetail/{id}','V1\NewsController@getNewsDetail');
    });
    //私人定制
    Route::prefix('person')->group(function (){
        Route::get('getPersonCateLists','V1\PersonController@getPersonCateLists');
        Route::post('addPerson','V1\PersonController@addPerson');
    });
    //商城
    Route::prefix('shop')->group(function (){
        Route::get('getLists','V1\ShopController@getLists');
        Route::get('getCateLists','V1\ShopController@getCateLists');
        Route::get('getShopDetail/{id}','V1\ShopController@getShopDetail');
        Route::post('createOrder','V1\ShopController@createOrder');
        Route::get('getOrderLists','V1\ShopController@getOrderLists');
        Route::get('getOrderDetail','V1\ShopController@getOrderDetail');
        Route::post('comment','V1\ShopController@comment');
    });
    //用户
    Route::prefix('user')->group(function (){
        Route::get('getUserAccount','V1\UserController@getUserAccount');
        Route::post('exchangeCash','V1\UserController@exchangeCash');
        Route::post('withdraw','V1\UserController@withdraw');
        Route::get('getWalletList','V1\UserController@getWalletList');
        Route::get('getCushLists','V1\UserController@getCushLists');
        Route::get('getPromoterLists','V1\UserController@getPromoterLists');
        Route::get('getScoreList','V1\UserController@getScoreList');
        Route::get('getExRecordLists','V1\UserController@getExRecordLists');
        Route::get('getAddressLists','V1\UserController@getAddressLists');
        Route::post('addUserAddress','V1\UserController@addUserAddress');
        Route::post('editUserAddress','V1\UserController@editUserAddress');
        Route::post('delUserAddress','V1\UserController@delUserAddress');
        Route::post('setDefaultUserAddress','V1\UserController@setDefaultUserAddress');
    });
    //预约
    Route::prefix('book')->group(function (){
        Route::get('getBookLists','V1\BookController@getApiBookLists');
        Route::post('addBook','V1\BookController@addBook');
        Route::get('getBookDetail/{id}','V1\BookController@getBookDetail');
    });
    //商品兑换
    Route::prefix('exchange')->group(function (){
        Route::get('getCatelist','V1\ExchangeController@getCatelist');
        Route::get('getApiGoodsDetail','V1\ExchangeController@getApiGoodsDetail');
        Route::post('createOrder','V1\ExchangeController@createOrder');
    });
});

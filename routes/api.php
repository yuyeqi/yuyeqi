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
Route::group(['prefix'=>'v1', 'namespace'=>'Api'],function (){
    //注册
    Route::prefix('public')->group(function (){
        Route::get('getUserCateLists','V1\PublicController@getUserCateLists');
        Route::post('register','V1\PublicController@register');
    });
    //首页
    Route::prefix('index')->group(function (){
        Route::get('slideshow','V1\IndexController@getSlideShowLists');
        Route::get('getUserInfo','V1\IndexController@getUserInfo');
        Route::get('getNewsGoods','V1\IndexController@getNewsGoods');
        Route::get('getNewsLists','V1\IndexController@getNewsLists');
        Route::get('getCaseLists','V1\IndexController@getCaseLists');
        Route::get('getCasesDetail','V1\IndexController@getCasesDetail');
    });
    //商城
    Route::prefix('shop')->group(function (){
        Route::get('getLists','V1\ShopController@getLists');
        Route::get('getCateLists','V1\ShopController@getCateLists');
        Route::get('getShopDetail','V1\ShopController@getShopDetail');
    });
    //新闻
    Route::prefix('news')->group(function (){
        Route::get('getNewsPageLists','V1\NewsController@getNewsPageLists');
        Route::get('getNewsDetail','V1\NewsController@getNewsDetail');
    });
    //微信
    Route::prefix('wechat')->group(function (){
        Route::get('auth','V1\WxController@auth');
        Route::get('getJsConfig','V1\WxController@getJsConfig');
        Route::get('getAppCode','V1\WxController@getAppCode');
    });

});
//需要登陆
Route::group(['prefix'=>'v1', 'namespace'=>'Api'],function (){
    //私人定制
    Route::prefix('person')->group(function (){
        Route::get('getPersonCateLists','V1\PersonController@getPersonCateLists');
        Route::post('addPerson','V1\PersonController@addPerson');
    });
    //商城
    Route::prefix('shop')->group(function (){
        Route::post('createOrder','V1\ShopController@createOrder');
        Route::get('getOrderLists','V1\ShopController@getOrderLists');
        Route::get('getOrderDetail','V1\ShopController@getOrderDetail');
        Route::post('comment','V1\ShopController@comment');
    });
    //用户
    Route::prefix('user')->group(function (){
        Route::get('getUserAccount','V1\UserController@getUserAccount');
        Route::post('exchangeCash','V1\UserController@exchangeCash');
        Route::get('exchangeCash','V1\UserController@exchangeCash');
        Route::get('getAccountInfo','V1\UserController@getAccountInfo');
        Route::get('getScoreInfo','V1\UserController@getScoreInfo');
        Route::get('withdraw','V1\UserController@withdraw');
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
        Route::get('getBookDetail','V1\BookController@getBookDetail');
    });
    //商品兑换
    Route::prefix('exchange')->group(function (){
        Route::get('getCatelist','V1\ExchangeController@getCatelist');
        Route::get('getGoodsLists','V1\ExchangeController@getGoodsLists');
        Route::get('getApiGoodsDetail','V1\ExchangeController@getApiGoodsDetail');
        Route::post('createOrder','V1\ExchangeController@createOrder');
    });
});

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
Route::group(['prefix'=>'v1', 'namespace'=>'Api'],function (){
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
    });
});

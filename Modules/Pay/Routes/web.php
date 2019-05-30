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

Route::prefix('pay')->group(function() {


    //Route::get('/', 'PayController@index');

    //支付宝支付后的返回地址
    Route::get('payment/alipay/return', 'NotifyController@alipayReturn')->name('payment.alipay.return');

    //支付宝支付后的回调地址
    Route::get('payment/alipay/notify', 'NotifyController@alipayNotify')->name('payment.alipay.notify');

    //微信支付后的返回地址
    Route::get('payment/wechat/return', 'NotifyController@alipayReturn')->name('payment.wechat.return');

    //微信支付后的回调地址
    Route::get('payment/wechat/notify', 'NotifyController@alipayNotify')->name('payment.wechat.notify');
});

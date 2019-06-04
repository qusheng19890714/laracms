<?php

namespace Modules\Pay\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\FrontController;

class NotifyController extends FrontController
{

    //支付宝前端回调页面
    public function alipayReturn()
    {
        try {
            app('alipay')->verify();
        } catch (\Exception $e) {

            return '支付失败';

            //return view('pages.error', ['msg' => '数据不正确']);
        }

        return '支付成功';

    }


    //支付宝服务端回调
    public function alipayNotify()
    {
        $data = app('alipay')->verify();
    }


    //支付宝前端回调页面
    public function wechatReturn()
    {
        try {
            app('wechat_pay')->verify();
        } catch (\Exception $e) {

            return '支付失败';

            //return view('pages.error', ['msg' => '数据不正确']);
        }

        return '支付成功';

    }


    //支付宝服务端回调
    public function wechatNotify()
    {
        $data = app('wechat_pay')->verify();
    }
}
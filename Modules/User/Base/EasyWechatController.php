<?php

namespace Modules\User\Base;

use Modules\Core\Base\BaseController;
use EasyWeChat\Factory;

/**
 * easy-wechat 基类
 * Class WeixinController
 * @package Modules\User\Base
 */
class EasyWechatController extends BaseController
{
    //easy-wechat实例
    protected $weixin;

    public function __construct()
    {
        parent::__construct();

        $config = [

            'app_id' => config('app.weixin_appid'),
            'secret' => config('app.weixin_appsecret'),
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',
        ];

        $this->weixin = Factory::officialAccount($config);
    }

}
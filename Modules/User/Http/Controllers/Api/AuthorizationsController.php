<?php
namespace Modules\User\Http\Controllers\Api;

use Modules\Core\Base\ApiController;
use Modules\User\Http\Requests\Api\AuthorizationRequest;

/**
 * 第三方登录
 * Class AuthorizationsController
 * @package Modules\User\Http\Controllers\Api
 */
class AuthorizationsController extends ApiController
{
    public function socialStore($type, AuthorizationRequest $request)
    {
        //目前支持的第三方
        if(!in_array($type, ['weixin'])) {



        }
    }
}

<?php
namespace Modules\User\Http\Controllers\Api;

use Modules\Core\Base\ApiController;
use Modules\User\Entities\User;
use Modules\User\Http\Requests\Api\AuthorizationRequest;
use Modules\User\Entities\AuthorConfig;
use Modules\User\Entities\Authorization;

/**
 * 第三方登录
 * Class AuthorizationsController
 * @package Modules\User\Http\Controllers\Api
 */
class AuthorizationsController extends ApiController
{

    public function socialStore($type, AuthorizationRequest $request)
    {

        $authorizations = AuthorConfig::where('status', 1)->pluck('res_name')->toArray();

        //目前支持的第三方
        if(!in_array($type, $authorizations)) {

            return $this->response->errorBadRequest();

        }

        //var_dump(config('services.weixin.client_id'));
        //exit;
        $driver = \Socialite::driver($type);



            if ($code = $request->code) {

                $response = $driver->getAccessTokenResponse($code);

                $token = array_get($response, 'access_token');

            }else {

                $token = $request->access_token;

                if ($type == 'weixin') {

                    $driver->setOpenId($request->openid);
                }
            }

            $oauthUser = $driver->userFromToken($token);



        switch($type)
        {
            case 'weixin' :

                $unionid = $oauthUser->offsetExists('unionid') ? $oauthUser->offsetGet('unionid') : null;

                if ($unionid) {

                    $authorization = Authorization::where('type', $type)->where('union_id', $unionid)->first();
                }else {

                    $authorization = Authorization::where('type', $type)->where('open_id', $oauthUser->getId())->first();
                }

                //创建用户
                if (!$authorization) {

                    $user = User::create([

                        'name'     => $oauthUser->getNickName(),
                        'avatar'   => $oauthUser->getAvatar(),
                        'user_from'=> $type,

                    ]);

                    Authorization::create([

                        'user_id'    => $user->id,
                        'user_name'  => $oauthUser->getNickName(),
                        'type'       => $type,
                        'union_id'   => $unionid,
                        'open_id'    => $oauthUser->getId()
                    ]);
                }

                break;


        }

    }
}

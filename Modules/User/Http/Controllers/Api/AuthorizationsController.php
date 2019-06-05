<?php
namespace Modules\User\Http\Controllers\Api;

use Illuminate\Support\Facades\Hash;
use Modules\Core\Base\ApiController;
use Modules\User\Entities\User;
use Modules\User\Http\Requests\Api\AuthorizationRequest;
use Modules\User\Http\Requests\Api\SocialAuthorizationRequest;
use Modules\User\Entities\AuthorConfig;
use Modules\User\Entities\Authorization;

/**
 * 第三方登录
 * Class AuthorizationsController
 * @package Modules\User\Http\Controllers\Api
 */
class AuthorizationsController extends ApiController
{

    public function socialStore($type, SocialAuthorizationRequest $request)
    {

        $authorizations = AuthorConfig::where('status', 1)->pluck('res_name')->toArray();

        //目前支持的第三方
        if(!in_array($type, $authorizations)) {

            return $this->response->errorBadRequest();

        }

        //var_dump(config('services.weixin.client_id'));
        //exit;
        $driver = \Socialite::driver($type);

        try{

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

        }catch (\Exception $e) {

            return $this->response->errorUnauthorized('参数错误，未获取用户信息');
        }


        switch($type)
        {
            case 'weixin' :

                $unionid = $oauthUser->offsetExists('unionid') ? $oauthUser->offsetGet('unionid') : null;

                if ($unionid) {

                    $authorization = Authorization::where('type', $type)->where('unionid', $unionid)->first();
                }else {

                    $authorization = Authorization::where('type', $type)->where('identifier', $oauthUser->getId())->first();
                }

                //创建用户
                if (!$authorization) {

                    $user = User::create([

                        'name'     => $oauthUser->getNickName(),
                        'avatar'   => $oauthUser->getAvatar(),

                    ]);

                    $authorization = Authorization::create([

                        'user_id'    => $user->id,
                        'type'       => $type,
                        'unionid'    => $unionid,
                        'identifier' => $oauthUser->getId(),
                        'verified'   => 1,
                        'ip'         => request()->ip(),
                    ]);
                }

                break;
        }

        $token=\Auth::guard('api')->fromUser($authorization);

        return $this->respondWithToken($token);

    }

    /**
     * 手机和邮箱登录
     * @param AuthorizationRequest $request
     */
    public function store(AuthorizationRequest $request)
    {

        $user = Authorization::where('type', $request->type)->where('identifier', $request->username)->where('verified', 1)->where('status', 1)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {

            return $this->response->errorUnauthorized(trans('user::user.login.error.tip'));
        }

        $token = \Auth::guard('api')->fromUser($user);

        return $this->respondWithToken($token)->setStatusCode(201);

    }


    protected function respondWithToken($token)
    {
        return $this->response->array([

            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }
}

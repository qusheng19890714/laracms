<?php

use Illuminate\Http\Request;

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['namespace'=>'Module\User\Http\Controllers\Api', 'middleware'=>['serializer:array', 'bindings']], function($api) {

    //登录节流
    $api->group(['middleware'=>'api.throttle',
                 'limit'=> config('api.rate_limits.sign.limit'),
                  'expires' =>config('api.rate_limits.sign.expires')

    ], function($api) {




    });



});

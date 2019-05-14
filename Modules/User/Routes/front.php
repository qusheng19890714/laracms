<?php
use Illuminate\Routing\Router;

// User 模块前台路由
$router->group(['prefix' =>'user','module'=>'user'], function (Router $router) {

    // 首页
    $router->get('/', 'IndexController@index')->name('user.index');
});

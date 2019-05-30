<?php
use Illuminate\Routing\Router;

// Pay 模块前台路由
$router->group(['prefix' =>'pay','module'=>'pay'], function (Router $router) {

    // 首页
    $router->get('/', 'IndexController@index')->name('pay.index');
});

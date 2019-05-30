<?php
use Illuminate\Routing\Router;

// Order 模块前台路由
$router->group(['prefix' =>'order','module'=>'order'], function (Router $router) {

    // 首页
    $router->get('/', 'IndexController@index')->name('order.index');
});

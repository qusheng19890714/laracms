<?php
use Illuminate\Routing\Router;

// Topic 模块前台路由
$router->group(['prefix' =>'topic','module'=>'topic'], function (Router $router) {

    // 首页
    $router->get('/', 'IndexController@index')->name('topic.index');
    $router->get('{topic}/{slug?}', 'TopicController@show')->name('topics.show');
});

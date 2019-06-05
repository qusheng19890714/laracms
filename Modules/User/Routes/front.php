<?php
use Illuminate\Routing\Router;

// User 模块前台路由
$router->group(['prefix' =>'user','module'=>'user'], function (Router $router) {

    // 首页
    $router->get('/', 'WeixinController@index')->name('user.weixin.index');

    //登录页面
    $router->get('login', 'LoginController@showLoginForm')->name('user.login');

    //登录
    $router->post('login', 'LoginController@login')->name('user.login.store');

    //注册页面
    $router->get('register', 'RegisterController@showRegistrationForm')->name('user.register');

    //注册
    $router->post('register', 'RegisterController@register')->name('user.register.store');

    //退出登录
    $router->post('logout', 'LoginController@logout')->name('user.logout');

});

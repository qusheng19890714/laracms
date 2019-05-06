<?php

use Illuminate\Routing\Router;

// Core 模块后台路由
$router->group(['prefix' =>'core', 'module'=>'core'], function (Router $router) {

    //模块列表
    $router->get('module', 'ModuleController@index')->name('core.module.index');
    //模块安装
    $router->post('module/install/{module}', 'ModuleController@install')->name('core.module.install');
    //模块卸载
    $router->post('module/uninstall/{module}', 'ModuleController@uninstall')->name('core.module.uninstall');


    //系统配置
    $router->get('config', 'ConfigController@index')->name('core.config.index');

    //获取语言option
    $router->get('config/lang', 'ConfigController@lang')->name('core.config.lang');

    //获取时区option
    $router->get('config/timezone', 'ConfigController@timezone')->name('core.config.timezone');

    //修改语言时区配置
    $router->post('config/local', 'ConfigController@localStore')->name('core.config.local.store');

    //修改环境配置
    $router->post('config/safe', 'ConfigController@safeStore')->name('core.config.safe.store');


});
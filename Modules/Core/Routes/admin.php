<?php

use Illuminate\Routing\Router;

// Core 模块后台路由
$router->group(['prefix' =>'core', 'module'=>'core', 'middleware'=>config('admin.route.middleware')], function (Router $router) {

    //模块列表
    $router->get('module', 'ModuleController@index')->name('core.module.index');
    //模块安装
    $router->post('module/install', 'ModuleController@install')->name('core.module.install');
    //模块卸载
    $router->post('module/uninstall', 'ModuleController@uninstall')->name('core.module.uninstall');

    //系统配置
    $router->get('config', 'ConfigController@index')->name('core.config.index');

    //修改语言时区配置
    $router->post('config/local', 'ConfigController@localStore')->name('core.config.local.store');

    //修改环境配置
    $router->post('config/safe', 'ConfigController@safeStore')->name('core.config.safe.store');

    //编辑器上传图片
    $router->post('config/upload', 'ConfigController@upload')->name('core.upload.store');



});
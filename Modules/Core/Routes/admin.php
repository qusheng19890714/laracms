<?php

use Illuminate\Routing\Router;

// Core 模块后台路由
$router->group(['prefix' =>'core', 'module'=>'core'], function (Router $router) {

    $router->get('/', 'ConfigController@index')->name('core.config.index');
    $router->get('module', 'ModuleController@index')->name('core.module.index');
    $router->post('module/install/{module}', 'ModuleController@install')->name('core.module.install');
    $router->post('module/uninstall/{module}', 'ModuleController@uninstall')->name('core.module.uninstall');

});
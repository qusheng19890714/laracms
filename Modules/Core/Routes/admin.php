<?php

use Illuminate\Routing\Router;

// Core 模块后台路由
$router->group(['prefix' =>'core', 'module'=>'core'], function (Router $router) {

    $router->get('/', 'ConfigController@index')->name('core.config.index');

});
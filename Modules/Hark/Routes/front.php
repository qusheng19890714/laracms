<?php
use Illuminate\Routing\Router;

// Hark 模块前台路由
$router->group(['prefix' =>'hark','module'=>'hark'], function (Router $router) {

    // 首页
    $router->get('export', 'ExportController@exportCanberraGoodsSell')->name('hark.export.goods');
});

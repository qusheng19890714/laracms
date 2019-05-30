<?php
use Illuminate\Routing\Router;

// Order 模块后台路由
$router->group(['prefix'=>'order', 'module'=>'order'], function (Router $router) {

    // 单个路由示例
    //$router->get('/', 'IndexController@index')->name('order.index')->middleware('allow:order.index');

    // 群组路由示例
    // $router->group(['prefix' =>'example'], function (Router $router) {
    //    $router->get('index','ExampleController@index')->name('order.example.index')->middleware('allow:order.example.index');
    //    $router->get('create','ExampleController@create')->name('order.example.create')->middleware('allow:order.example.create');
    //    $router->post('store','ExampleController@store')->name('order.example.store')->middleware('allow:order.example.store');
    //    $router->get('edit/{id}','AdministratorController@edit')->name('order.example.edit')->middleware('allow:order.example.edit');
    //    $router->put('update/{id}','AdministratorController@update')->name('order.example.update')->middleware('allow:order.example.update');
    //    $router->delete('destroy/{id}','AdministratorController@destroy')->name('order.example.destroy')->middleware('allow:order.example.destroy');
    // });

});

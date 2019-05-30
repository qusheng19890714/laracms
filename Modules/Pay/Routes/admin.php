<?php
use Illuminate\Routing\Router;

// Pay 模块后台路由
$router->group(['prefix'=>'pay', 'module'=>'pay'], function (Router $router) {

    // 单个路由示例
    //$router->get('/', 'IndexController@index')->name('pay.index')->middleware('allow:pay.index');

    // 群组路由示例
    // $router->group(['prefix' =>'example'], function (Router $router) {
    //    $router->get('index','ExampleController@index')->name('pay.example.index')->middleware('allow:pay.example.index');
    //    $router->get('create','ExampleController@create')->name('pay.example.create')->middleware('allow:pay.example.create');
    //    $router->post('store','ExampleController@store')->name('pay.example.store')->middleware('allow:pay.example.store');
    //    $router->get('edit/{id}','AdministratorController@edit')->name('pay.example.edit')->middleware('allow:pay.example.edit');
    //    $router->put('update/{id}','AdministratorController@update')->name('pay.example.update')->middleware('allow:pay.example.update');
    //    $router->delete('destroy/{id}','AdministratorController@destroy')->name('pay.example.destroy')->middleware('allow:pay.example.destroy');
    // });

});

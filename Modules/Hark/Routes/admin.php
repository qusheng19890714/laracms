<?php
use Illuminate\Routing\Router;

// Hark 模块后台路由
$router->group(['prefix'=>'hark', 'module'=>'hark'], function (Router $router) {

    // 单个路由示例
    //$router->get('/', 'IndexController@index')->name('hark.index')->middleware('allow:hark.index');

    // 群组路由示例
    // $router->group(['prefix' =>'example'], function (Router $router) {
    //    $router->get('index','ExampleController@index')->name('hark.example.index')->middleware('allow:hark.example.index');
    //    $router->get('create','ExampleController@create')->name('hark.example.create')->middleware('allow:hark.example.create');
    //    $router->post('store','ExampleController@store')->name('hark.example.store')->middleware('allow:hark.example.store');
    //    $router->get('edit/{id}','AdministratorController@edit')->name('hark.example.edit')->middleware('allow:hark.example.edit');
    //    $router->put('update/{id}','AdministratorController@update')->name('hark.example.update')->middleware('allow:hark.example.update');
    //    $router->delete('destroy/{id}','AdministratorController@destroy')->name('hark.example.destroy')->middleware('allow:hark.example.destroy');
    // });

});

<?php
use Illuminate\Routing\Router;

// User 模块后台路由
$router->group(['prefix'=>'user', 'module'=>'user'], function (Router $router) {

    // 单个路由示例
    //$router->get('/', 'IndexController@index')->name('user.index')->middleware('allow:user.index');

    // 群组路由示例
    // $router->group(['prefix' =>'example'], function (Router $router) {
    //    $router->get('index','ExampleController@index')->name('user.example.index')->middleware('allow:user.example.index');
    //    $router->get('create','ExampleController@create')->name('user.example.create')->middleware('allow:user.example.create');
    //    $router->post('store','ExampleController@store')->name('user.example.store')->middleware('allow:user.example.store');
    //    $router->get('edit/{id}','AdministratorController@edit')->name('user.example.edit')->middleware('allow:user.example.edit');
    //    $router->put('update/{id}','AdministratorController@update')->name('user.example.update')->middleware('allow:user.example.update');
    //    $router->delete('destroy/{id}','AdministratorController@destroy')->name('user.example.destroy')->middleware('allow:user.example.destroy');
    // });

    $router->resource('users',IndexController::class);
    $router->resource('authorconfig', AuthorConfigController::class);

    $router->post('authorconfig/configstore', 'AuthorConfigController@config_store')->name('user.authorconfig.config.store');

});

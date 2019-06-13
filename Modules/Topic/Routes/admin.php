<?php
use Illuminate\Routing\Router;

// Topic 模块后台路由
$router->group(['prefix'=>'topic', 'module'=>'topic'], function (Router $router) {

    // 单个路由示例
    //$router->get('/', 'IndexController@index')->name('topic.index')->middleware('allow:topic.index');

    // 群组路由示例
    // $router->group(['prefix' =>'example'], function (Router $router) {
    //    $router->get('index','ExampleController@index')->name('topic.example.index')->middleware('allow:topic.example.index');
    //    $router->get('create','ExampleController@create')->name('topic.example.create')->middleware('allow:topic.example.create');
    //    $router->post('store','ExampleController@store')->name('topic.example.store')->middleware('allow:topic.example.store');
    //    $router->get('edit/{id}','AdministratorController@edit')->name('topic.example.edit')->middleware('allow:topic.example.edit');
    //    $router->put('update/{id}','AdministratorController@update')->name('topic.example.update')->middleware('allow:topic.example.update');
    //    $router->delete('destroy/{id}','AdministratorController@destroy')->name('topic.example.destroy')->middleware('allow:topic.example.destroy');
    // });

    $router->resource('categories', CategoriesController::class);
    $router->resource('topics', TopicsController::class);
    //$router->resource('replies', RepliesController::class);

    $router->get('topic/{topic}/replies', 'RepliesController@replies')->name('topic.reply'); //文章评论
    $router->get('topic/{topic}/replies/create', 'RepliesController@create')->name('replies.create');
    $router->post('topic/{topic}/replies/', 'RepliesController@store')->name('replise.store');
    $router->get('topic/{topic}/replies/{reply}/edit', 'RepliesController@edit')->name('replies.edit');
    $router->patch('topic/{topic}/replies/{reply}', 'RepliesController@update')->name('replies.update');
    $router->delete('topic/{topic}/replies/{reply}', 'RepliesController@destroy')->name('replies.destroy');
});

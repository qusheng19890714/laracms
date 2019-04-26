<?php
/**
 * cms安装路由
 */
// 安装 //'welcome','check','config','modules','installing','finished'
Route::group(['prefix' => 'install', 'namespace' => 'App\Http\Controllers','middleware' => ['web']], function() {

    Route::get('/', 'InstallController@welcome')->name('install.welcome');

    Route::get('check', 'InstallController@check')->name('install.check');

    Route::any('config', 'InstallController@config')->name('install.config');

    Route::any('database', 'InstallController@database')->name('install.database');

    Route::any('modules', 'InstallController@modules')->name('install.modules');

    Route::get('finished', 'InstallController@finished')->name('install.finished');
});
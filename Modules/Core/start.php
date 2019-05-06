<?php

/**
 * 扩展$module->install(), 安装模块
 */
\Nwidart\Modules\Module::macro('install', function($force=false, $seed=true) {

    $name   = $this->getLowerName();

    if (!$force && $this->json()->get('installed', 0)) {
        abort(403, 'This module has been installed');
    }

    $this->register();

    $this->fireEvent('installing');

    // 迁移数据库
    \Artisan::call('module:migrate', ['module'=>$name, '--force'=>true]);

    if ($seed) {
        \Artisan::call('module:seed', ['module' => $name, '--force'=>true]);
    }

    // 载入配置
    $config = $this->path.'/config.php';
    if (\File::exists($config) && $configs = require $config) {
        \Modules\Core\Entitties\Config::set($name, $configs);
    }

    // 发布数据
    \Artisan::call('module:publish', ['module' => $name]);

    // 更新 module.json
    $this->json()->set('active', 1)->set('installed', 1)->save();


    $this->fireEvent('installed');

    // 重启
    \Artisan::call('reboot');
});

/**
 * 扩展$module->uninstall(), 卸载模块
 */
\Nwidart\Modules\Module::macro('uninstall', function() {

    $name = $this->getLowerName();

    // 核心模块不能卸载
    if (in_array($name, config('modules.cores',['core']))) {
        abort(403, 'This is a core module that does not allow operation!');
    }

    $this->fireEvent('uninstalling');

    // 卸载模块数据表
    \Artisan::call('module:migrate-reset', ['module'=>$name]);

    // 删除模块配置
    \Modules\Core\Entitties\Config::forget($name);

    // 删除已经发布的资源文件
    \File::deleteDirectory(\Module::assetPath($name));

    // 删除模块缓存
    \File::delete(app()->bootstrapPath("cache/{$this->getSnakeName()}_module.php"));

    // 更新 module.json
    $this->json()->set('active', 0)->set('installed', 0)->save();

    $this->fireEvent('uninstalled');

    // 重启
    \Artisan::call('reboot');
});


/**
 * 扩展Module::getFileData方法, 获取文件返回数据
 */
\Nwidart\Modules\Facades\Module::macro('getFileData', function($module, $file, array $args=[], $default=null) {
    return $this->find($module)->getFileData($file, $args, $default);
});

/**
 * 扩展data方法, 从data目录获取文件返回的数据
 *
 * @param   $name 模块::文件名称
 * @param   $args 额外参数
 * @example Module::data('tinymce::tools.default', $attrs)
 * @return array
 */
\Nwidart\Modules\Facades\Module::macro('data', function($name, array $args=[], $default=null) {
    list($module, $file) = explode('::', $name);
    $data = static::getFileData($module, "Data/{$file}.php", $args, $default);
    //return \Filter::fire($name, $data, $args, $default);
    return $data;
});

/**
 * 扩展$module->getFileData, 获取文件返回的数据
 */
\Nwidart\Modules\Module::macro('getFileData', function($file, array $args=[], $default=null) {
    $file = $this->getExtraPath($file);

    if (! $this->app['files']->isFile($file)) {
        return $default;
    }

    return value(function() use ($file, $args) {
        @extract($args);
        return require $file;
    });
});
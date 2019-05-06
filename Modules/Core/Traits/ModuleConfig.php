<?php

namespace Modules\Core\Traits;

use File;
use Artisan;
use Module;


trait ModuleConfig
{

    /**
     * 设置ENV
     *
     * @param  string $key   键名，如：APP_ENV
     * @param  string $value 键值，如：local，如果为null，则为删除
     * @return bool
     */
    private function setenv($key, $value='')
    {
        $envs = [];

        if (is_string($key)) {
            $envs = [$key => $value];
        }

        if (is_array($key)) {
            $envs = array_merge($envs, $key);
        }

        foreach ($envs as $key => $value) {
            Artisan::call('env:set',['key' => strtoupper($key), 'value'=>$value]);
        }

        // 重启
        Artisan::call('reboot');

        return $this;
    }
}

<?php

use Barryvdh\Debugbar\Facades\Debugbar;

if (!function_exists('fire_hook_filter')) {
    /**
     * 触发一个过滤器钩子
     *
     * @param string $hookName
     * @param mixed $data
     * @return mixed
     */
    function fire_hook_filter(string $hookName, mixed $data): mixed
    {
        // 检查应用是否启用了调试工具
        if (config('app.debug') && has_debugbar()) {
            // 使用调试工具记录日志
            Debugbar::log('HOOK === fire_hook_filter: ' . $hookName);
        }

        // 调用应用的eventy实例的filter方法，传递钩子名称和数据，返回过滤后的数据
        return app('eventy')->filter($hookName, $data);
    }
}

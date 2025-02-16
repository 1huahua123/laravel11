<?php

use Barryvdh\Debugbar\Facades\Debugbar;

if (!function_exists('fire_hook_action')) {
    /**
     * 触发钩子动作的函数
     *
     * @param string     $hookName 钩子的名称
     * @param mixed|null $data     可选参数，传递给钩子的数据
     */
    function fire_hook_action(string $hookName, mixed $data = null): void
    {
        // 检查应用是否处于调试模式并且调试工具栏是否存在
        if (config('app.debug') && has_debugbar()) {
            // 使用调试工具栏记录钩子触发信息
            Debugbar::log('HOOK === fire_hook_action: ' . $hookName);
        }
        // 调用事件管理器，触发指定名称的钩子，并传递数据
        app('eventy')->action($hookName, $data);
    }
}

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

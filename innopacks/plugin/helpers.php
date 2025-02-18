<?php

use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Str;

if (!function_exists('plugin_locale_code')) {

    /**
     * 获取当前环境的区域设置代码。
     *
     * 根据当前用户是否在后台管理界面，返回不同的区域设置代码。
     *
     * @return string 当前环境的区域设置代码。
     */
    function plugin_locale_code(): string
    {
        // 检查当前用户是否在后台管理界面
        if (is_admin()) {
            // 如果是后台管理界面，调用 panel_locale_code() 函数获取区域设置代码
            return panel_locale_code();
        }

        // 如果不是后台管理界面，调用 front_locale_code() 函数获取区域设置代码
        return front_locale_code();
    }
}

if (!function_exists('plugin_setting')) {

    /**
     * 获取插件设置值
     *
     * 该函数用于获取指定插件的设置值。如果提供了键名，则返回该键对应的设置值；
     * 如果未提供键名，则返回整个插件的设置数组。
     *
     * @param string     $code    插件的代码标识
     * @param string     $key     插件设置的键名，默认为空字符串
     * @param mixed|null $default 如果指定键名不存在时返回的默认值，默认为 null
     * @return mixed 返回指定键名的设置值或整个插件的设置数组
     */
    function plugin_setting(string $code, string $key = '', mixed $default = null): mixed
    {
        // 将插件代码标识转换为蛇形命名（例如：CamelCase 转换为 camel_case）
        $code = Str::snake($code);
        // 如果提供了键名
        if ($key) {
            // 返回该键对应的设置值，如果键不存在则返回默认值
            return setting("$code.$key", $default);
        }

        // 如果未提供键名，则返回整个插件的设置数组
        return setting("$code", $default);
    }
}

if (!function_exists('is_admin')) {

    /**
     * 检查当前请求是否是管理员面板的请求
     *
     * @return bool 如果是管理员面板的请求，返回true；否则返回false
     */
    function is_admin(): bool
    {
        // 获取管理员面板的名称
        $adminName = panel_name();
        // 获取当前请求的URI
        $uri = request()->getRequestUri();
        // 检查当前请求的URI是否以管理员面板的名称开头
        if (Str::startsWith($uri, "/{$adminName}")) {
            // 如果是管理员面板的请求，返回true
            return true;
        }

        // 如果不是管理员面板的请求，返回false
        return false;
    }
}

if (!function_exists('panel_name')) {

    /**
     * 获取面板名称的函数。
     *
     * 该函数用于获取系统设置中的面板名称。如果系统设置中没有定义面板名称，
     * 则返回默认值 'panel'。
     *
     * @return string 返回面板名称。
     */
    function panel_name(): string
    {
        // 调用 system_setting 函数获取面板名称，默认值为 'panel'
        // 如果 system_setting 返回的值为 null 或 false，则使用三元运算符返回 'panel'
        return system_setting('panel_name', 'panel') ?: 'panel';
    }
}

if (!function_exists('current_admin')) {

    /**
     * 获取当前登录的管理员用户
     *
     * 该函数通过调用 Laravel 的认证系统中的 'admin' 守卫来获取当前登录的管理员用户。
     * 'admin' 守卫是在 Laravel 应用中配置的，用于管理管理员用户的认证。
     *
     * @return Authenticatable|null 返回当前登录的管理员用户对象，如果没有管理员用户登录，则返回 null。
     */
    function current_admin(): ?Authenticatable
    {
        // 使用 Laravel 的 auth 助手函数，指定 'admin' 守卫来获取当前登录的用户
        // auth('admin') 表示使用 'admin' 守卫进行认证
        // user() 方法用于获取当前登录的用户对象
        return auth('admin')->user();
    }
}

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
     * @param mixed  $data
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

<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/18 20:08
 * @Project    : laravel11
 * @Description: 辅助函数文件
 */

if (!function_exists('panel_locale_code')) {

    /**
     * 获取当前管理员的语言区域代码
     *
     * 该函数首先尝试从当前管理员对象中获取语言区域代码，
     * 如果当前管理员对象不存在或者没有设置语言区域代码，
     * 则返回会话中的语言区域代码。
     *
     * @return string 当前管理员或会话的语言区域代码
     */
    function panel_locale_code(): string
    {
        return current_admin()->locale ?? panel_session_locale();
    }
}

if (!function_exists('panel_session_locale')) {

    /**
     * 获取面板会话的本地化设置
     *
     * 该函数用于从会话中获取面板的本地化设置。如果会话中没有设置本地化，
     * 则调用 setting_locale_code() 函数来获取默认的本地化代码。
     *
     * @return string 返回面板的本地化代码
     */
    function panel_session_locale(): string
    {
        // 从会话中获取 'panel_locale' 的值，如果不存在则返回 setting_locale_code() 的结果
        return session('panel_locale', setting_locale_code());
    }
}

if (!function_exists('panel_trans')) {

    /**
     * 获取面板翻译字符串
     *
     * 该函数用于获取指定键的面板翻译字符串。它通过调用Laravel框架的trans函数来实现。
     *
     * @param string|null $key     要翻译的键，默认为null。如果为null，则返回整个面板翻译组。
     * @param array       $replace 替换数组，用于替换翻译字符串中的占位符，默认为空数组。
     * @param string|null $locale  语言环境，指定要使用的语言环境，默认为null，表示使用当前语言环境。
     * @return string 返回翻译后的字符串。
     */
    function panel_trans(string $key = null, array $replace = [], string $locale = null): string
    {
        // 调用Laravel的trans函数，获取指定键的翻译字符串
        // 'panel/' . $key 构造翻译文件的路径，例如 'panel/welcome'
        // $replace 用于替换翻译字符串中的占位符
        // $locale 指定语言环境，如果为null则使用当前语言环境
        return trans('panel/' . $key, $replace, $locale);
    }
}

if (!function_exists('panel_route')) {

    /**
     * 生成面板路由
     * @param string $name       路由名称
     * @param mixed  $parameters 路由参数
     * @param bool   $absolute   是否生成绝对路径
     * @return string 路由URL
     */
    function panel_route(string $name, mixed $parameters = [], bool $absolute = true): string
    {
        // 获取面板名称
        $panelName = panel_name();
        try {
            // 尝试生成指定面板的路由
            return route($panelName . '.' . $name, $parameters, $absolute);
        } catch (Exception $e) {
            // 如果生成路由时发生异常，则返回面板的默认路由
            return route($panelName . '.dashboard.index');
        }
    }
}

if (!function_exists('panel_name')) {

    /**
     * 获取面板名称
     * @return string 面板名称
     */
    function panel_name(): string
    {
        // 从系统设置中获取面板名称，默认为 'panel'
        return system_setting('panel_name', 'panel') ?: 'panel';
    }
}

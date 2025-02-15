<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/15 18:18
 * @Project    : laravel11
 * @Description: 该文件定义了一个名为ViewHook的类，用于处理视图钩子相关的功能。
 */

namespace InnoShop\Common\Libraries;

class ViewHook
{
    /**

     * 获取ViewHook类的单例实例
     * @return ViewHook 返回ViewHook类的实例
     */
    public static function getInstance(): ViewHook
    {
        return new self;
    }

    /**

     * 根据调用栈信息获取钩子名称
     * @param $trace - 调用栈信息数组
     * @return string 返回钩子名称
     */
    public function getHookName($trace): string
    {
        // 从调用栈中获取调用类的名称，如果不存在则返回空字符串
        $class = $trace[1]['class'] ?? '';
        if (empty($class)) {
            return '';
        }

        // 从调用栈中获取调用方法的名称，并转换为小写，如果不存在则返回空字符串
        $method = strtolower($trace[1]['function'] ?? '');
        if (empty($method)) {
            return '';
        }

        // 检查类名是否以"InnoShop"开头，如果不是则返回空字符串
        if (!str_starts_with($class, 'InnoShop')) {
            return '';
        }

        // 移除类名中的"InnoShop\"、"Controllers\"和"Controller"部分，并将命名空间分隔符"\"替换为"."
        $class = str_replace(['InnoShop\\', 'Controllers\\', 'Controller'], '', $class);
        $class = strtolower(str_replace('\\', '.', $class));

        // 返回拼接后的钩子名称
        return "$class.$method";
    }
}

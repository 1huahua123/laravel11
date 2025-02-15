<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/15 15:27
 * @Project    : laravel11
 * @Description: 定义一个API钩子类，用于获取API调用时的钩子名称
 */

namespace InnoShop\Common\Libraries;

class ApiHook
{
    /**
     * 获取ApiHook类的实例
     * @return ApiHook 返回ApiHook类的实例
     */
    public static function getInstance(): ApiHook
    {
        return new self;
    }

    /**
     * 根据调用栈获取钩子名称
     * @param array $trace 调用栈信息
     * @return string 钩子名称
     */
    public function getHookName(array $trace): string
    {
        // 获取调用栈中的类名
        $class = $trace[1]['class'] ?? '';
        // 如果类名为空，返回空字符串
        if (empty($class)) {
            return '';
        }

        // 获取调用栈中的方法名，并转换为小写
        $method = strtolower($trace[1]['function'] ?? '');
        // 如果方法名为空，返回空字符串
        if (empty($method)) {
            return '';
        }

        // 检查类名是否以"InnoShop\RestAPI"开头，如果不是，返回空字符串
        if (!str_starts_with($class, 'InnoShop\RestAPI')) {
            return '';
        }

        // 移除类名中的"InnoShop\", "ApiControllers", "Controller"部分
        $class = str_replace(['InnoShop\\', 'ApiControllers', 'Controller'], '', $class);
        // 将类名中的反斜杠替换为点，并转换为小写
        $class = strtolower(str_replace('\\', '.', $class));

        // 返回类名和方法名组合的钩子名称
        return "$class.$method";
    }
}

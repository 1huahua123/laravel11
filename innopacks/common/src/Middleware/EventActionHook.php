<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/16 15:35
 * @Project    : laravel11
 * @Description: 事件动作钩子中间件，用于在请求处理前后触发自定义钩子
 */

namespace InnoShop\Common\Middleware;

use Closure;
use Illuminate\Http\Request;

class EventActionHook
{
    /**

     * 处理请求并触发相应的钩子
     *
     * @param Request  $request  当前请求对象
     * @param Closure $next     请求处理完毕后的下一个中间件
     * @return mixed             返回处理后的响应
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // 解析当前请求的钩子名称
        $hookName = $this->parseHookName($request);
        // 触发前置钩子
        fire_hook_action($hookName . '.before', $request);

        // 处理请求
        $response = $next($request);

        // 将响应合并到请求中
        $request->merge(['response' => $response]);
        // 触发后置钩子
        fire_hook_action($hookName . 'after', $request);

        // 返回处理后的响应
        return $response;
    }

    /**

     * 解析请求对应的钩子名称
     *
     * @param Request $request  当前请求对象
     * @return string           返回解析后的钩子名称
     */
    private function parseHookName(Request $request): string
    {
        // 获取当前请求的路由信息
        $route = $request->route();
        // 获取路由对应的控制器动作名称
        $controllerAction = $route->getActionName();
        // 移除命名空间中的特定部分
        $controllerAction = str_replace(['InnoShop\\', 'Controllers\\'], '', $controllerAction);
        // 将控制器和动作名称用反斜杠分隔
        $controllerAction = str_replace('Controller@', '\\', $controllerAction);
        // 去除首尾的反斜杠
        $controllerAction = trim($controllerAction, '\\');

        // 将反斜杠替换为点，并转换为小写
        return strtolower(str_replace('\\', '.', $controllerAction));
    }
}

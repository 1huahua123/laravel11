<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/16 15:29
 * @Project    : laravel11
 * @Description: 这是一个中间件类，用于处理请求和响应的钩子过滤。
 */

namespace InnoShop\Common\Middleware;

use Closure;
use Illuminate\Http\Request;

class ContentFilterHook
{
    /**
     * 处理传入的请求，并对其进行过滤，然后传递给下一个中间件。
     * 在接收到响应后，对响应进行过滤并返回。
     *
     * @param Request $request 传入的HTTP请求对象
     * @param Closure $next 下一个中间件的处理函数
     * @return mixed 处理后的HTTP响应对象
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // 解析钩子名称
        $hookName = $this->parseHookName($request);

        // 触发钩子过滤请求
        $filteredRequest = fire_hook_filter($hookName . '.request', $request);

        // 将过滤后的请求传递给下一个中间件
        $response = $next($filteredRequest);

        // 触发钩子过滤响应
        $filteredResponse = fire_hook_filter($hookName . '.request', $response);

        // 如果过滤后的响应是字符串，则设置响应内容
        if (is_string($filteredResponse)) {
            $response->setContent($filteredResponse);
        }

        // 返回处理后的响应
        return $response;
    }

    /**

     * 解析请求中的控制器动作，生成钩子名称。
     *
     * @param Request $request 传入的HTTP请求对象
     * @return string 钩子名称
     */
    private function parseHookName(Request $request): string
    {
        // 获取当前路由信息
        $route = $request->route();

        // 获取控制器动作名称
        $controllerAction = $route->getActionName();
        // 移除命名空间中的特定部分
        $controllerAction = str_replace(['InnoShop\\', 'Controllers\\'], '', $controllerAction);
        // 将控制器名称和动作名称用反斜杠分隔
        $controllerAction = str_replace('Controller@', '\\', $controllerAction);

        // 将反斜杠替换为点，并转换为小写
        return strtolower(str_replace('\\', '.', $controllerAction));
    }
}

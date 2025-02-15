<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/15 15:30
 * @Project    : laravel11
 * @Description: 面包屑导航类，用于生成不同类型的导航路径
 */

namespace InnoShop\Common\Libraries;

class Breadcrumb
{
    /**
     * 获取Breadcrumb类的实例
     * @return Breadcrumb 返回Breadcrumb类的实例
     */
    public static function getInstance(): Breadcrumb
    {
        return new self;
    }

    /**
     * 获取面包屑导航路径
     * @param string $type   导航类型
     * @param mixed  $object 导航对象
     * @param string $title  导航标题，默认为空字符串
     * @return array 返回导航路径数组，包含标题和URL
     */
    public function getTrail(string $type, mixed $object, string $title = ''): array
    {
        // 检查类型是否为特定类型之一
        if (in_array($type, ['category', 'product', 'catalog', 'article', 'page', 'tag'])) {
            return [
                'title' => $object->translation->name ?: $object->translation->title, // 获取对象的名称或标题
                'url'   => $object->url // 获取对象的URL
            ];
        } elseif ($type == 'brand') {
            // 如果类型为品牌
            return [
                'title' => $object->name, // 获取品牌的名称
                'url'   => $object->url // 获取品牌的URL
            ];
        } elseif ($type == 'order') {
            // 如果类型为订单
            return [
                'title' => $object->number, // 获取订单号
                'url'   => account_route('orders.show', ['order' => $object->id]) // 生成订单展示的URL
            ];
        } elseif ($type == 'order_return') {
            // 如果类型为退货订单
            return [
                'title' => $object->number, // 获取退货订单号
                'url'   => account_route('order_returns.show', ['order_return' => $object->id]) // 生成退货订单展示的URL
            ];
        } elseif ($type == 'route') {
            // 如果类型为路由
            if (empty($title)) {
                $title = front_trans('common.' . str_replace('.', '_', $object)); // 生成默认标题
            }

            return [
                'title' => $title, // 使用传入的标题或默认标题
                'url'   => front_trans($object) // 生成路由的URL
            ];
        } elseif ($type == 'static') {
            // 如果类型为静态
            return [
                'title' => $title, // 使用传入的标题
                'url'   => $object // 使用传入的对象作为URL
            ];
        }

        // 如果类型不匹配，返回空数组
        return [];
    }
}

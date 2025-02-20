<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/20 21:40
 * @Project    : laravel11
 * @Description: 定义一个抽象类 BaseBoot，用于插件的基本启动逻辑
 */

namespace InnoShop\Plugin\Core;

use InnoShop\Plugin\Resources\PluginResource;

abstract class BaseBoot
{
    /**
     * 定义一个受保护的 Plugin 类型的属性 $plugin，用于存储插件实例
     *
     * @var Plugin
     */
    protected Plugin $plugin;

    /**
     * 定义一个受保护的 PluginResource 类型的属性 $pluginResource，用于存储插件资源实例
     *
     * @var PluginResource
     */
    protected PluginResource $pluginResource;

    /**
     * 构造函数，用于初始化插件和插件资源
     */
    public function __construct()
    {
        // 获取当前类的完整类名
        $className            = static::class;
        // 使用 explode 函数将类名按命名空间分割成数组
        $names                = explode('\\', $className);
        // 获取第二个元素，即插件的空间名
        $spaceName            = $names[1];
        // 使用 Laravel 的 app 函数获取插件管理实例，并调用 getPlugin 方法获取当前插件的实例
        $this->plugin         = app('plugin')->getPlugin($spaceName);
        // 创建一个新的 PluginResource 实例，传入插件实例
        $this->pluginResource = new PluginResource($this->plugin);
    }

    /**
     * 定义一个抽象方法 init，要求子类实现具体地初始化逻辑
     *
     * @return void
     */
    abstract public function init(): void;
}

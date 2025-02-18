<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/18 19:52
 * @Project    : laravel11
 * @Description: 插件仓库类，用于管理已安装的插件
 */

namespace InnoShop\Plugin\Repositories;

use Illuminate\Database\Eloquent\Collection;
use InnoShop\Plugin\Models\PluginModel;

class PluginRepo
{
    /**
     * 定义一个静态属性，用于存储已安装的插件集合
     *
     * @var Collection
     */
    public static Collection $installedPlugins;

    /**
     * 构造函数中初始化已安装插件集合为空集合
     */
    public function __construct()
    {
        self::$installedPlugins = new Collection;
    }

    /**
     * 静态方法，用于获取PluginRepo类的实例
     *
     * @return PluginRepo
     */
    public static function getInstance(): PluginRepo
    {
        return new self;
    }

    /**
     * 检查指定插件的激活状态
     *
     * @param string $pluginCode 插件的代码标识
     * @return bool 返回插件是否激活的布尔值
     */
    public function checkActive(string $pluginCode): bool
    {
        // 调用setting函数获取插件激活状态的配置值
        // setting函数的参数是插件的代码标识加上'.active'，形成如 'pluginCode.active' 的键名
        // setting函数返回的值可能是null、字符串'0'或'1'等，需要转换为布尔值
        return (bool)setting("{$pluginCode}.active");
    }

    /**
     * 检查指定插件代码是否已安装
     *
     * @param string $code 插件的代码
     * @return bool 如果插件已安装则返回true，否则返回false
     */
    public function installed(string $code): bool
    {
        // 调用getPluginsGroupCode()方法获取所有已安装插件的代码集合
        // 然后使用has()方法检查集合中是否包含指定的插件代码
        return $this->getPluginsGroupCode()->has($code);
    }

    /**
     * 根据插件代码获取插件的优先级
     *
     * @param $pluginCode
     * @return int
     */
    public function getPriority($pluginCode): int
    {
        $plugin = $this->getPluginByCode($pluginCode);
        if (empty($plugin)) {
            return 0;
        }

        return (int)$plugin->priority;
    }

    /**
     * 根据给定的代码获取对应的插件闭包函数
     *
     * @param $code
     * @return mixed
     */
    public function getPluginByCode($code): mixed
    {
        return $this->getPluginsGroupCode()->get($code);
    }

    /**
     * 获取插件组代码
     *
     * 该方法用于获取所有插件的代码，并将它们按照代码进行分组。
     * 返回的是一个集合对象，其中每个元素的键是插件的代码。
     *
     * @return Collection|\Illuminate\Support\Collection 返回一个集合对象，其中包含按代码分组的插件。
     */
    public function getPluginsGroupCode(): Collection|\Illuminate\Support\Collection
    {
        // 调用allPlugins方法获取所有插件的数据集合
        $allPlugins = $this->allPlugins();

        // 使用keyBy方法将插件集合按照每个插件的 'code' 字段进行分组
        // 这样返回的集合中，每个元素的键就是插件的代码
        return $allPlugins->keyBy('code');
    }

    /**
     * 获取所有已安装的插件
     *
     * @return Collection
     */
    public function allPlugins(): Collection
    {
        // 检查静态属性 self::$installedPlugins 中的插件数量是否大于 0
        if (self::$installedPlugins->count() > 0) {
            // 如果已安装插件数量大于 0，直接返回 self::$installedPlugins
            return self::$installedPlugins;
        }

        // 如果已安装插件数量为 0，则从数据库中获取所有插件
        // 使用 PluginModel::all() 方法获取所有插件数据
        // 并将获取到的插件数据赋值给静态属性 self::$installedPlugins
        return self::$installedPlugins = PluginModel::all();
    }
}

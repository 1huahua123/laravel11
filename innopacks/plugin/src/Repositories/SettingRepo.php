<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/16 18:14
 * @Project    : laravel11
 * @Description: 管理插件设置仓库
 */
namespace InnoShop\Plugin\Repositories;

use InnoShop\Common\Models\SettingModel;
use InnoShop\Common\Repositories\SettingRepo as CommonSettingRepo;
class SettingRepo extends CommonSettingRepo
{
    /**
     * 获取插件激活状态的字段配置
     * @return array 返回字段配置数组
     */
    public function getPluginActiveField(): array
    {
        // 返回字段配置数组
        return [
            'name' => 'active', // 字段名称
            'label' => panel_trans('common.status'), // 字段标签，使用panel_trans函数进行多语言翻译
            'type' => 'bool', // 字段类型为布尔值
            'required' => true // 字段是否必填
        ];
    }

    /**
     * 获取插件可用平台的字段配置
     * @return array 返回字段配置数组
     */
    public function getPluginAvailableField(): array
    {
        // 返回字段配置数组
        return [
            'name' => 'available', // 字段名称
            'label' => panel_trans('common.available'), // 字段标签，使用panel_trans函数进行多语言翻译
            'type' => 'checkbox', // 字段类型为复选框
            'options' => [ // 可选值数组
                ['label' => 'PC WEB', 'value' => 'pc_web'], // PC网页
                ['label' => 'Mobile Web', 'value' => 'mobile_web'], // 移动网页
                ['label' => 'Wechat Mini', 'value' => 'wechat_mini'], // 微信小程序
                ['label' => 'Wechat Official', 'value' => 'wechat_official'], // 微信公众号
                ['label' => 'APP', 'value' => 'app'] // 应用程序
            ],
            'required' => true, // 字段是否必填
            'rules' => 'required' // 验证规则，要求字段必填
        ];
    }

    /**
     * 根据插件代码获取插件的字段配置
     * @param $pluginCode - 插件代码
     * @return mixed 返回查询结果
     */
    public function getPluginFields($pluginCode): mixed
    {
        // 使用SettingModel进行数据库查询
        return SettingModel::query()
            ->where('space', $pluginCode) // 根据插件代码筛选
            ->get() // 获取查询结果
            ->keyBy('name'); // 将结果按字段名称作为键进行索引
    }
}

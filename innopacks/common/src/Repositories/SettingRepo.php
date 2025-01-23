<?php

namespace InnoShop\Common\Repositories;

use Illuminate\Database\Eloquent\Builder;
use InnoShop\Common\Models\SettingModel;
use Throwable;

/**
 * Class SettingRepo
 * @package InnoShop\Common\Repositories
 * @author 华锐
 * @desc 设置仓库
 */
class SettingRepo extends BaseRepo
{
    /**
     * 获取设置查询构造器
     *
     * @param array $filters
     * @return Builder
     */
    public function builder(array $filters = []): Builder
    {
        $builder = SettingModel::query();
        $space   = $filters['space'] ?? '';
        if ($space) {
            $builder->where('space', $space);
        }

        $name = $filters['name'] ?? '';
        if ($name) {
            $builder->where('name', $name);
        }

        return fire_hook_filter('repo.setting.builder', $builder);
    }

    /**
     * 获取设置的所有记录
     *
     * @return array
     */
    public function groupedSettings(): array
    {
        $settings = SettingModel::all(['space', 'name', 'value', 'json']);

        $result = [];
        foreach ($settings as $setting) {
            $space = $setting->space;
            $name  = $setting->name;
            $value = $setting->value;
            if ($setting->json) {
                $result[$space][$name] = json_decode($value, true);
            } else {
                $result[$space][$name] = $value;
            }
        }

        return $result;
    }

    /**
     * 批量更新设置值
     *
     * @param $settings - 设置数组 ['name' => 'value']
     * @param string $space - 设置所属空间
     * @return void
     * @throws Throwable
     */
    public function updateValues($settings, string $space = 'system'): void
    {
        foreach ($settings as $name => $value) {
            if (in_array($name, ['_method', '_token'])) {
                continue;
            }
            $this->updateValue($name, $value, $space);
        }
    }

    /**
     * 更新系统设置值
     *
     * @param $name - 设置名称
     * @param $value - 设置值
     * @return SettingModel|null
     * @throws Throwable
     */
    public function updateSystemValue($name, $value): ?SettingModel
    {
        return $this->updateValue($name, $value, 'system');
    }

    /**
     * 更新插件的设置值
     *
     * @param $code - 插件代码
     * @param $name - 设置名称
     * @param $value - 设置值
     * @return SettingModel|null
     * @throws Throwable
     */
    public function updatePluginValue($code, $name, $value): ?SettingModel
    {
        return $this->updateValue($name, $value, $code);
    }

    /**
     * 更新设置值
     *
     * @param $name - 设置名称
     * @param $value - 设置值
     * @param string $space 设置所属空间
     * @return SettingModel|null - 返回设置模型
     * @throws Throwable
     */
    private function updateValue($name, $value, string $space): ?SettingModel
    {
        if ($value === null) {
            $value = '';
        }

        $setting = $this->builder(['space' => $space, 'name' => $name])->first();

        $settingData = [
            'space' => $space,
            'name'  => $name,
            'value' => is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value,
            'json'  => is_array($value)
        ];

        if (empty($setting)) {
            $setting = new SettingModel($settingData);
            $setting->saveOrFail();
        } else {
            $setting->update($settingData);
        }

        return $setting;
    }
}

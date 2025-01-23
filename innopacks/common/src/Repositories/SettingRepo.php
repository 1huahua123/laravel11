<?php

namespace InnoShop\Common\Repositories;

use Illuminate\Database\Eloquent\Builder;
use InnoShop\Common\Models\SettingModel;

/**
 * Class SettingRepo
 * @package InnoShop\Common\Repositories
 * @author 华锐
 * @desc 设置仓库
 */
class SettingRepo extends BaseRepo
{
    /**
     *  获取设置查询构造器
     *
     * @param array $filters
     * @return Builder
     */
    public function builder(array $filters = []): Builder
    {
        $builder = SettingModel::query();
        $space = $filters['space'] ?? '';
        if ($space) {
            $builder->where('space', $space);
        }

        $name = $filters['name'] ?? '';
        if ($name) {
            $builder->where('name', $name);
        }

        return fire_hook_filter('repo.setting.builder', $builder);
    }
}

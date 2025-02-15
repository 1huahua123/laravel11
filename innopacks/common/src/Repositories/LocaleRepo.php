<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/15 15:37
 * @Project    : laravel11
 * @Description: 本文件定义了一个LocaleRepo类，用于处理语言环境相关的数据操作。
 */

namespace InnoShop\Common\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use InnoShop\Common\Models\LocaleModel;

class LocaleRepo extends BaseRepo
{
    // 定义一个静态变量，用于缓存已启用的语言环境集合
    public static ?Collection $enabledLocales = null;

    /**
     * 获取活跃的语言环境列表
     *
     * @return Collection|null 返回活跃的语言环境集合
     */
    public function getActiveList(): ?Collection
    {
        // 如果已缓存了活跃的语言环境集合，则直接返回
        if (self::$enabledLocales !== null) {
            return self::$enabledLocales;
        }

        // 否则，查询数据库获取活跃的语言环境集合，并缓存
        return self::$enabledLocales = $this->builder(['active' => true])->orderBy('position')->get();
    }

    /**
     * 构建查询构建器
     *
     * @param array $filters 过滤条件
     * @return Builder 返回查询构建器实例
     */
    public function builder(array $filters = []): Builder
    {
        // 初始化查询构建器
        $builder = LocaleModel::query();

        // 如果有传入code过滤条件，则添加到查询构建器中
        $code = $filters['code'] ?? '';
        if ($code) {
            $builder->where('code', $code);
        }

        // 如果有传入active过滤条件，则添加到查询构建器中
        if (isset($filters['active'])) {
            $builder->where('active', (bool)$filters['active']);
        }

        // 触发钩子，允许其他地方对查询构建器进行修改
        return fire_hook_filter('repo.locale.builder', $builder);
    }
}

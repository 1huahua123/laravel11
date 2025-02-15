<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/15 15:53
 * @Project    : laravel11
 * @Description: 货币仓库类，用于处理货币相关的数据操作
 */

namespace InnoShop\Common\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use InnoShop\Common\Models\CurrencyModel;

class CurrencyRepo extends BaseRepo
{
    /**
     * 获取所有启用的货币列表
     *
     * @return Collection
     */
    public function enabledList(): Collection
    {
        // 调用withActive方法获取启用的货币查询构建器，并执行get方法获取结果
        return $this->withActive()->builder()->get();
    }

    /**
     * 获取货币查询构建器，并根据传入的过滤条件进行筛选
     *
     * @param array $filters 过滤条件数组
     * @return Builder
     */
    public function builder(array $filters = []): Builder
    {
        // 初始化货币模型的查询构建器
        $builder = CurrencyModel::query();

        // 获取过滤条件中的名称
        $name = $filters['name'] ?? '';
        if ($name) {
            // 如果名称存在，则添加名称模糊匹配的查询条件
            $builder->where('name', 'like', "%$name%");
        }

        // 获取过滤条件中的代码
        $code = $filters['code'] ?? '';
        if ($code) {
            // 如果代码存在，则添加代码模糊匹配的查询条件
            $builder->where('code', 'like', "%$code%");
        }

        // 检查过滤条件中是否包含活跃状态
        if (isset($filters['active'])) {
            // 如果存在，则添加活跃状态的查询条件
            $builder->where('active', (bool)$filters['active']);
        }

        // 获取过滤条件中的关键词
        $keyword = $filters['keyword'] ?? '';
        if ($keyword) {
            // 如果关键词存在，则添加名称或代码模糊匹配的查询条件
            $builder->where(function ($query) use ($keyword) {
                $query->where('name', 'like', "%$keyword%")
                    ->orWhere('code', 'like', "%$keyword%");
            });
        }

        // 触发钩子，允许在查询构建器上添加额外的过滤条件
        return fire_hook_filter('repo.currency.builder', $builder);
    }
}

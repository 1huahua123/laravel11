<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/15 14:33
 * @Project    : laravel11
 * @Description: 国家模型
 */

// 定义命名空间，用于组织代码和避免类名冲突
namespace InnoShop\Common\Models;

// 引入HasMany关系类，用于定义模型间的一对多关系
use Illuminate\Database\Eloquent\Relations\HasMany;

// CountryModel类继承自BaseModel，表示国家模型
class CountryModel extends BaseModel
{
    // 指定数据库表名为 'countries'
    protected $table = 'countries';

    // 定义可批量赋值的字段，包括 'name', 'code', 'continent', 'position', 'active'
    protected $fillable = ['name', 'code', 'continent', 'position', 'active'];

    /**
     * 国家与州关联
     *
     * @return HasMany
     */
    public function states(): HasMany
    {
        return $this->hasMany(StateModel::class, 'country_id', 'id');
    }
}

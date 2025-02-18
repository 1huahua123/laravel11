<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/18 19:54
 * @Project    : laravel11
 * @Description: 插件模型类，用于操作插件数据表
 */

namespace InnoShop\Plugin\Models;

use InnoShop\Common\Models\BaseModel;

class PluginModel extends BaseModel
{
    // 指定该模型对应的数据库表名，这里为 'plugins'
    protected $table = 'plugins';

    // 定义可批量赋值的字段，即允许通过 mass assignment（批量赋值）方式填充的字段
    // 这里包括 'type', 'code', 'priority' 三个字段
    protected $fillable = ['type', 'code', 'priority'];
}

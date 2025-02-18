<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/16 18:15
 * @Project    : laravel11
 * @Description: 这是一个设置模型的类，用于管理设置数据。
 */

namespace InnoShop\Plugin\Models;

use InnoShop\Common\Models\BaseModel;

class SettingModel extends BaseModel
{
    // 指定该模型对应的数据库表名
    protected $table = 'settings';

    // 定义允许批量赋值的字段列表
    // 这意味着在创建或更新模型实例时，可以一次性传入这些字段的值
    protected $fillable = ['space', 'name', 'value', 'json'];
}

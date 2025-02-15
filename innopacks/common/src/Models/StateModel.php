<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/15 14:45
 * @Project    : laravel11
 * @Description: 国家州模型
 */

namespace InnoShop\Common\Models;

class StateModel extends BaseModel
{
    // 定义表名
    protected $table = 'states';

    // 定义可填充字段
    protected $fillable = ['country_id', 'country_code', 'name', 'code', 'position', 'active'];
}

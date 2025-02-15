<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/15 15:52
 * @Project    : laravel11
 * @Description: 这是一个货币模型的类定义，用于处理货币相关的数据操作。
 */

namespace InnoShop\Common\Models;

class CurrencyModel extends BaseModel
{
    // 指定当前模型对应的数据库表名
    protected $table = 'currencies';

    // 定义允许被批量赋值的字段，这些字段可以通过Mass Assignment（批量赋值）方式进行赋值
    protected $fillable = ['name', 'code', 'symbol_left', 'symbol_right', 'decimal_place', 'value', 'active'];
}

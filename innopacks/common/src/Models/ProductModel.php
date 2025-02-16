<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/16 15:45
 * @Project    : laravel11
 * @Description: 产品模型类，用于处理产品相关的数据操作
 */

namespace InnoShop\Common\Models;

use InnoShop\Common\Traits\HasPackageFactory;
use InnoShop\Common\Traits\Replicate;
use InnoShop\Common\Traits\Translatable;

class ProductModel extends BaseModel
{
    use HasPackageFactory, Replicate, Translatable;

    // 指定该模型对应的数据库表名
    protected $table = 'products';

    // 定义可批量赋值的字段列表，这些字段可以通过Mass Assignment（批量赋值）方式进行赋值
    protected $fillable = [
        'brand_id', 'product_image_id', 'product_video_id', 'product_sku_id', 'tax_class_id',
        'slug', 'is_virtual', 'variables', 'active', 'weight', 'weight_class', 'sales', 'viewed'
    ];

    // 定义字段类型转换规则，将variables字段从JSON字符串转换为数组
    protected $casts = [
        'variables' => 'array'
    ];
}

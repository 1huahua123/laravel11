<?php

namespace InnoShop\Common\Models;

/**
 * Class SettingModel
 * @package InnoShop\Common\Models
 * @author 华锐
 * @desc 设置模型
 */
class SettingModel extends BaseModel
{
    /**
     * 定义一个受保护的属性 $fillable，用于指定可以批量赋值的字段列表
     *
     * @var string[]
     */
    protected $fillable = [
        'space', 'name', 'value', 'json'
    ];
}

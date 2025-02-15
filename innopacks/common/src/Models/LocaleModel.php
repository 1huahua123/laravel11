<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/15 15:36
 * @Project    : laravel11
 * @Description: 本文件定义了一个LocaleModel类，用于处理与语言环境相关的数据模型。
 */

namespace InnoShop\Common\Models;

class LocaleModel extends BaseModel
{
    protected $table = 'locales';

    // $fillable属性定义了可以被批量赋值的字段列表
    // 这在Laravel框架中用于防止批量赋值漏洞，只允许指定的字段被批量赋值
    protected $fillable = ['name', 'code', 'position', 'active'];
}

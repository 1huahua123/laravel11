<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/16 15:41
 * @Project    : laravel11
 * @Description: 定义一个用于生成模型工厂的trait
 */

namespace InnoShop\Common\Traits;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

trait HasPackageFactory
{
    use HasFactory;

    /**
     * 创建一个新的模型工厂实例
     *
     * 这个方法重写了父类的方法，用于根据模型的命名空间自动生成对应的工厂类实例。
     *
     * @return object 返回一个新的模型工厂实例
     */
    protected static function newFactory(): object
    {
        // 获取当前调用类的命名空间前缀（不包括'Models\'部分）
        $package = Str::before(get_called_class(), 'Models\\');
        // 获取当前调用类的名称（不包括'Models\'部分）
        $modelName = Str::after(get_called_class(), 'Models\\');
        // 构建工厂类的完整命名空间路径
        $path = $package . 'Factories\\' . $modelName . 'Factory';

        // 返回新的工厂类实例
        return new $path;
    }
}

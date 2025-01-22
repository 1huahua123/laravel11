<?php

namespace InnoShop\Common;

use Illuminate\Support\ServiceProvider;

/**
 * Class CommonServiceProvider
 * @package InnoShop\Common
 * @author 华锐
 * @desc 公共服务提供者
 */
class CommonServiceProvider extends ServiceProvider
{
    /**
     * 配置文件路径
     *
     * @var string
     */
    private string $basePath = __DIR__ . '/../';
}

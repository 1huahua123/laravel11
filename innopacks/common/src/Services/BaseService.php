<?php

namespace InnoShop\Common\Services;

/**
 * Class BaseService
 * @package InnoShop\Common\Services
 * @author 华锐
 * @desc 服务基类
 */
class BaseService
{
    /**
     * 获取实例
     *
     * @return static
     */
    public static function getInstance(): static
    {
        return new static;
    }
}

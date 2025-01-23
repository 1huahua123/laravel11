<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

/**
 * Class AuthServiceProvider
 * @package App\Providers
 * @author 华锐
 * @desc 权限服务提供者
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * 定义一个受保护的属性$policies，用于存储策略映射，即模型与策略类的对应关系
     *
     * @var array
     */
    protected $policies = [];

    /**
     * 注册任何认证/授权服务
     *
     * @return void
     */
    public function boot(): void
    {
        // 调用父类的registerPolicies方法，注册策略映射
        $this->registerPolicies();

        // 使用Gate的before方法定义一个全局的权限检查逻辑
        // 这个逻辑会在任何权限检查之前执行
        Gate::before(function ($user, $ability) {
            // 如果用户的id是1，则直接返回true，表示拥有所有权限
            return $user->id == 1;
        });
    }
}

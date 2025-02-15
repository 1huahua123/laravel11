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

    public function boot(): void
    {
        load_settings();
        $this->registerConfig();
    }

    /**
     * 注册配置文件
     *
     * @return void
     */
    private function registerConfig(): void
    {
        $this->mergeConfigFrom($this->basePath . 'config/innoshop.php', 'innoshop');
    }

    private function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands();
        }
    }

    /**
     * 加载视图模版
     *
     * @return void
     */
    private function loadViewTemplates(): void
    {
        // 获取原始视图路径
        $originViewPath = inno_path('common/resources/views');
        // 获取自定义视图路径
        $customViewPath = resource_path('views/vendor/common');

        // 使用publishes方法将原始视图发布到自定义视图路径
        $this->publishes([
            $originViewPath => $customViewPath
        ], 'views');

        // 从原始视图路径加载视图，并指定命令空间common，这样在视图中可以通过common::view_name的方式来引用视图
        $this->loadViewsFrom($originViewPath, 'common');
    }
}

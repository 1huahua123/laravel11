<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/16 17:43
 * @Project    : laravel11
 * @Description:
 */

namespace InnoShop\Plugin;

use Illuminate\Support\ServiceProvider;
use InnoShop\Plugin\Core\PluginManager;

class PluginServiceProvider extends ServiceProvider
{
    private string $basePath = __DIR__ . '/../';

    private string $pluginBasePath = '';

    public function register()
    {
        $this->app->singleton('plugin', function () {
            return new PluginManager();
        });
    }
}

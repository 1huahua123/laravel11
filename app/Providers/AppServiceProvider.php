<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 设置数据库默认字符串长度为255
        Schema::defaultStringLength(255);

        // 检查环境变量是否设置了强制使用 HTTPS
        if (env('APP_FORCE_HTTPS', false)) {
            // 强制使用 HTTPS
            URL::forceScheme('https');
        }
    }
}

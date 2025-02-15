<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/15 12:43
 * @Project    : laravel11
 * @Description: 发布前端主题自定义命令
 */

namespace InnoShop\Common\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class PublishFrontTheme extends Command
{
    // 定义命令行指令
    protected $signature = 'inno:publish-theme';

    // 定义命令行描述
    protected $description = 'Publish default theme for frontend.';

    // 处理命令行指令
    public function handle(): void
    {
        // 调用Artisan命令，发布前端主题
        Artisan::call('vendor:publish', [
            '--provider' => 'InnoShop\Front\FrontServiceProvider',
            '--tag'      => 'views'
        ]);

        // 输出Artisan命令的输出结果
        echo Artisan::output();
    }
}

<?php

namespace InnoShop\Common;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use InnoShop\Common\Components\Alert;
use InnoShop\Common\Components\Forms\Date;
use InnoShop\Common\Components\Forms\File;
use InnoShop\Common\Components\Forms\Image;
use InnoShop\Common\Components\Forms\Images;
use InnoShop\Common\Components\Forms\Input;
use InnoShop\Common\Components\Forms\RichText;
use InnoShop\Common\Components\Forms\SwitchRadio;
use InnoShop\Common\Components\Forms\Textarea;
use InnoShop\Common\Components\NoData;
use InnoShop\Common\Console\PublishFrontTheme;
use InnoShop\Common\Console\UpdateCountries;
use InnoShop\Common\Console\UpdateStates;

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
        $this->registerMigrations();
        $this->registerCommands();
        $this->loadMailSettings();
        $this->loadViewComponents();
        $this->loadViewTemplates();
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

    /**
     * 注册数据表迁移文件
     *
     * @return void
     */
    private function registerMigrations(): void
    {
        $this->loadMigrationsFrom($this->basePath . 'database/migrations');
    }

    /**
     * 定义一个私有方法 registerCommands，用于注册命令
     *
     * @return void
     */
    private function registerCommands(): void
    {
        // 检查应用是否在控制台环境中运行
        if ($this->app->runningInConsole()) {
            // 如果是在控制台环境中，注册以下命令
            $this->commands([
                UpdateCountries::class,
                UpdateStates::class,
                PublishFrontTheme::class
            ]);
        }
    }

    /**
     * 用于加载邮件设置
     *
     * @return void
     */
    private function loadMailSettings(): void
    {
        // 获取系统设置中的邮件引擎配置，并将其转换为小写
        $mailEngine = strtolower(system_setting('email_engine'));

        // 如果邮件引擎配置为空，则直接返回，不执行后续操作
        if (empty($mailEngine)) {
            return;
        }

        // 获取系统设置中的邮件地址，默认为空字符串
        $storeMailAddress = system_setting('email', '');

        // 设置邮件配置中的默认邮件引擎
        Config::set('mail.default', $mailEngine);
        // 设置邮件配置中的发件人地址
        Config::set('mail.from.address', $storeMailAddress);
        // 设置邮件配置中的发件人名称，使用应用配置中的应用名称
        Config::set('mail.from.name', config('app.name'));

        // 如果邮件引擎为 SMTP，则进行 SMTP 相关的配置设置
        if ($mailEngine == 'smtp') {
            // 设置 SMTP 邮件发送器的配置信息
            Config::set('mail.mailers.smtp', [
                'transport' => 'smtp', // 使用 SMTP 传输方式
                'host' => system_setting('smtp.host'), // SMTP 服务器地址
                'port' => system_setting('smtp.port'), // SMTP 服务器端口
                'encryption' => strtolower(system_setting('smtp.encryption')), // SMTP 加密方式，转换为小写
                'username' => system_setting('smtp_username'), // SMTP 用户名
                'password' => system_setting('smtp_password'), // SMTP 密码
                'timeout' => system_setting('smtp_timeout') // SMTP 超时时间
            ]);
        }
    }

    /**
     * 用于加载视图组件
     *
     * @return void
     */
    protected function loadViewComponents(): void
    {
        // 调用 loadViewComponentsAs 方法，将一组视图组件以 'common' 前缀注册
        $this->loadViewComponentsAs('common', [
            // 注册 'alert' 组件，对应的类是 Alert
            'alert' => Alert::class,
            // 注册 'form-input' 组件，对应的类是 Input
            'form-input' => Input::class,
            // 注册 'form-date' 组件，对应的类是 Date
            'form-date' => Date::class,
            // 注册 'form-image' 组件，对应的类是 Image
            'form-image' => Image::class,
            // 注册 'form-file' 组件，对应的类是 File
            'form-file' => File::class,
            // 注册 'form-images' 组件，对应的类是 Images
            'form-images' => Images::class,
            // 注册 'form-rich-text' 组件，对应的类是 RichText
            'form-rich-text' => RichText::class,
            // 注册 'form-switch-radio' 组件，对应的类是 SwitchRadio
            'form-switch-radio' => SwitchRadio::class,
            // 注册 'form-textarea' 组件，对应的类是 Textarea
            'form-textarea' => Textarea::class,
            // 注册 'no-data' 组件，对应的类是 NoData
            'no-data' => NoData::class
        ]);
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

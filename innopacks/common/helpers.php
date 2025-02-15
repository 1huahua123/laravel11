<?php

use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use InnoShop\Common\Repositories\LocaleRepo;
use InnoShop\Common\Repositories\SettingRepo;

if (!function_exists('front_trans')) {
    /**
     * 前端翻译函数
     *
     * 该函数用于获取前端语言包中的翻译文本。
     * 它通过调用Laravel框架的trans函数来实现，指定了语言包的命名空间为'front'。
     *
     * @param string|null $key     要翻译的键名，如果不提供则返回所有翻译
     * @param array       $replace 替换翻译文本中的占位符的数组
     * @param string|null $locale  指定语言环境，如果不提供则使用当前语言环境
     * @return string|array        返回翻译后的字符串或数组
     */
    function front_trans(string $key = null, array $replace = [], string $locale = null)
    {
        // 调用Laravel的trans函数，指定语言包的命名空间为 'front'，并传递键名、替换数组和语言环境
        return trans('front/' . $key, $replace, $locale);
    }
}

if (!function_exists('account_route')) {
    /**
     * 生成账户相关路由的URL
     *
     * @param string $name       路由名称
     * @param mixed  $parameters 路由参数，默认为空数组
     * @param bool   $absolute   是否生成绝对URL，默认为true
     * @return string 生成的URL
     */
    function account_route(string $name, mixed $parameters = [], bool $absolute = true): string
    {
        // 检查当前系统支持的语言数量是否为1
        if (count(locales()) == 1) {
            // 如果只支持一种语言，直接生成路由URL
            return route('front.account.' . $name, $parameters, $absolute);
        }

        // 如果支持多种语言，生成包含语言代码的路由URL
        return route(front_locale_code() . '.front.account.' . $name, $parameters, $absolute);
    }
}

if (!function_exists('locales')) {
    /**
     * 获取活跃的语言环境列表
     *
     * 该函数通过调用LocaleRepo类的静态方法getInstance来获取LocaleRepo的单例实例，
     * 然后调用该实例的getActiveList方法来获取当前活跃的语言环境列表。
     *
     * @return Collection
     */
    function locales(): Collection
    {
        return LocaleRepo::getInstance()->getActiveList();
    }
}

if (!function_exists('front_locale_code')) {
    /**
     * 获取当前前端语言代码
     *
     * 该函数首先尝试从会话（session）中获取语言代码（locale），
     * 如果会话中没有设置语言代码，则调用 setting_locale_code() 函数
     * 来获取默认的语言代码。
     *
     * @return string 当前前端语言代码
     */
    function front_locale_code(): string
    {
        // 尝试从会话中获取语言代码
        return session('locale') ?? setting_locale_code();
    }
}

if (!function_exists('setting_locale_code')) {
    /**
     * 获取当前设置的本地化语言代码
     *
     * 该函数用于获取前端显示的语言环境代码。首先尝试从系统设置中获取
     * 'front_locale' 配置项的值，如果未设置，则返回应用程序配置中的默认
     * 语言环境代码。如果应用程序配置中也未设置，则默认返回 'en'（英语）。
     *
     * @return string 当前设置的本地化语言代码
     */
    function setting_locale_code(): string
    {
        // 调用 system_setting 函数获取 'front_locale' 配置项的值
        // 如果该配置项未设置，则返回第二个参数指定的默认值
        // 第二个参数调用 config 函数获取 'app.locale' 配置项的值
        // 如果 'app.locale' 也未设置，则返回第三个参数指定的默认值 'en'
        return system_setting('front_locale', config('app.locale', 'en'));
    }
}

if (!function_exists('system_setting')) {
    /**
     * 获取系统设置值
     *
     * 该函数用于获取系统配置中的特定设置值。如果指定的键不存在，则返回默认值。
     *
     * @param string     $key     要获取的设置项的键名
     * @param mixed|null $default 如果指定的键不存在时返回的默认值，默认为 null
     * @return mixed           返回指定键的设置值，如果键不存在则返回默认值
     */
    function system_setting(string $key, mixed $default = null): mixed
    {
        // 调用 setting 函数，传入 "system.{$key}" 作为键名，并传入默认值
        // "system.{$key}" 表示将 "system." 与传入的 $key 拼接成一个完整的键名
        return setting("system.{$key}", $default);
    }
}

if (!function_exists('setting')) {
    /**
     * 获取配置项的值
     *
     * @param string     $key     配置项的键名
     * @param mixed|null $default 默认值，如果配置项不存在时返回该值
     * @return mixed           返回配置项的值，如果配置项不存在则返回默认值
     */
    function setting(string $key, mixed $default = null): mixed
    {
        // 调用config函数获取配置项的值，配置项的键名为"inno.{$key}"
        // 如果配置项不存在，则返回$default指定的默认值
        return config("inno.{$key}", $default);
    }
}

if (!function_exists('inno_path')) {
    /**
     * 获取InnoShop路径
     *
     * @param string $path
     * @return string
     */
    function inno_path(string $path): string
    {
        return base_path("innopacks/{$path}");
    }
}

if (!function_exists('load_settings')) {
    /**
     * 加载设置
     *
     * @return void
     */
    function load_settings(): void
    {
        if (!installed()) {
            return;
        }

        if (config('inno')) {
            return;
        }

        $result = SettingRepo::getInstance()->groupedSettings();
        config(['inno' => $result]);
    }
}

if (!function_exists('installed')) {
    /**
     * 检查是否已安装
     *
     * @return bool
     */
    function installed(): bool
    {
        try {
            if (Schema::hasTable('settings') && file_exists(storage_path('installed'))) {
                return true;
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return false;
        }

        return false;
    }
}

if (!function_exists('has_debugbar')) {
    /**
     * 是否存在debugbar
     *
     * @return bool
     */
    function has_debugbar(): bool
    {
        return class_exists(Debugbar::class);
    }
}

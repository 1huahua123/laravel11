<?php

use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use InnoShop\Common\Repositories\SettingRepo;

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

<?php

use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use InnoShop\Common\Repositories\SettingRepo;

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

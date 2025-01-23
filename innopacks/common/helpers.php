<?php

use Barryvdh\Debugbar\Facades\Debugbar;

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

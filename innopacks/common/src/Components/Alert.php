<?php

namespace InnoShop\Common\Components;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\View\Component;

/**
 * Class Alert
 * @package InnoShop\Common\Components
 * @author 华锐
 * @desc 提示框组件
 */
class Alert extends Component
{
    /**
     * 提示框类型，可选值：success、info、warning、danger等
     *
     * @var string|null
     */
    public ?string $type;

    /**
     * 提示框显示的消息内容
     *
     * @var string
     */
    public string $msg;

    /**
     * 是否显示关闭按钮
     *
     * @var bool
     */
    public bool $close;

    /**
     * 构造函数，初始化提示框组件
     *
     * @param string $msg 提示框显示的消息内容
     * @param string|null $type 提示框类型，可选值：success、info、warning、danger等
     * @param bool $close 是否显示关闭按钮
     */
    public function __construct(string $msg, ?string $type = 'success', bool $close = false)
    {
        $this->type = $type;
        $this->msg = $msg;
        $this->close = $close;
    }

    /**
     * 渲染提示框组件
     *
     * @return Factory|Htmlable|View|Application
     */
    public function render(): Factory|View|Application|Htmlable
    {
        return view('common::components.alert');
    }
}

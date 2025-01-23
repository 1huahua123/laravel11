<?php

namespace InnoShop\Common\Components\Forms;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Class SwitchRadio
 * @package InnoShop\Common\Components\Forms
 * @author 华锐
 * @desc 开关单选框组件
 */
class SwitchRadio extends Component
{
    /**
     * 开关单选框名称
     *
     * @var string
     */
    public string $name;

    /**
     * 开关单选框值
     *
     * @var bool
     */
    public bool $value;

    /**
     * 开关单选框标题
     *
     * @var string
     */
    public string $title;

    /**
     * 构造函数，初始化开关单选框组件
     *
     * @param string $name 开关单选框名称
     * @param bool|null $value 开关单选框值
     * @param string $title 开关单选框标题
     */
    public function __construct(string $name, ?bool $value, string $title)
    {
        $this->name  = $name;
        $this->title = $title;
        $this->value = (bool)$value;
    }

    /**
     * 渲染开关单选框组件
     *
     * @return View
     */
    public function render(): View
    {
        return view('panel::components.form.switch-radio');
    }
}

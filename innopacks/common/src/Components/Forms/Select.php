<?php

namespace InnoShop\Common\Components\Forms;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Class Select
 * @package InnoShop\Common\Components\Forms
 * @author 华锐
 * @desc Select组件
 */
class Select extends Component
{
    /**
     * 选择框的名称
     *
     * @var string
     */
    public string $name;

    /**
     * 指示选择框是否为必填项
     *
     * @var bool|null
     */
    public bool $required;

    /**
     * 选择框的当前值
     *
     * @var string|null
     */
    public ?string $value;

    /**
     * 选择框的标题
     *
     * @var string
     */
    public string $title;

    /**
     * 选择框的选项
     *
     * @var array
     */
    public array $options;

    /**
     * 选项的唯一标识符键名
     *
     * @var string|null
     */
    public ?string $key;

    /**
     * 选项的显示标签键名
     *
     * @var string|null
     */
    public ?string $label;

    /**
     * 是否显示空选项
     *
     * @var bool|null
     */
    public bool $emptyOption;

    /**
     * 构造函数，用于初始化选择框组件的属性
     *
     * @param string $name 选择框的名称
     * @param string|null $value 选择框的当前值，可以为空
     * @param string $title 选择框的标题
     * @param array $options 选择框的选项数组
     * @param string|null $key 选项的唯一标识符键名，默认为'value'
     * @param string|null $label 选项的显示标签键名，默认为'label'
     * @param bool|null $required 是否为必填项，默认为false
     * @param bool|null $emptyOption 是否显示空选项，默认为true
     */
    public function __construct
    (
        string  $name,
        ?string $value,
        string  $title,
        array   $options,
        ?string $key = 'value',
        ?string $label = 'label',
        ?bool   $required = false,
        ?bool   $emptyOption = true
    )
    {
        // 将传入的参数赋值给对应的属性
        $this->name        = $name;
        $this->value       = $value;
        $this->title       = $title;
        $this->options     = $options;
        $this->key         = $key;
        $this->label       = $label;
        $this->required    = $required;
        $this->emptyOption = $emptyOption;
    }

    /**
     * 渲染函数，用于返回选择框组件的视图
     *
     * @return View
     */
    public function render(): View
    {
        return view('panel::components.form.select');
    }
}

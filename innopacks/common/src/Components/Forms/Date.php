<?php

namespace InnoShop\Common\Components\Forms;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\View\Component;

/**
 * Class Date
 * @package InnoShop\Common\Components\Forms
 * @author 华锐
 * @desc 日期表单组件
 */
class Date extends Component
{
    /**
     * 表单元素的名称
     *
     * @var string
     */
    public string $name;

    /**
     * 表单元素的标题
     *
     * @var string
     */
    public string $title;

    /**
     * 表单元素的值
     *
     * @var string
     */
    public string $value;

    /**
     * 表单元素错误信息
     *
     * @var string
     */
    public string $error;

    /**
     * 表单元素的占位符
     *
     * @var string
     */
    public string $placeholder;

    /**
     * 表单元素的描述信息
     *
     * @var string
     */
    public string $description;

    /**
     * 表单元素的类型
     *
     * @var string
     */
    public string $type;

    /**
     * 表单元素是否必填
     *
     * @var bool
     */
    public bool $required;

    /**
     * 表单元素是否禁用
     *
     * @var bool
     */
    public bool $disabled;

    /**
     * 构造函数，初始化表单元素
     *
     * @param string $name 表单元素的名称
     * @param string $title 表单元素的标题
     * @param string|null $value 表单元素的值
     * @param bool $required 表单元素是否必填
     * @param string $error 表单元素错误信息
     * @param string $type 表单元素的类型
     * @param string $placeholder 表单元素的占位符
     * @param string $description 表单元素的描述信息
     * @param bool $disabled 表单元素是否禁用
     */
    public function __construct
    (
        string  $name,
        string  $title,
        ?string $value,
        bool    $required = false,
        string  $error = '',
        string  $type = 'date',
        string  $placeholder = '',
        string  $description = '',
        bool    $disabled = false
    )
    {
        $this->name        = $name;
        $this->title       = $title;
        $this->value       = html_entity_decode($value, ENT_QUOTES);
        $this->error       = $error;
        $this->placeholder = $placeholder;
        $this->type        = $type;
        $this->required    = $required;
        $this->description = $description;
        $this->disabled    = $disabled;
    }

    /**
     * 渲染视图
     *
     * @return Factory|Htmlable|View|Application
     */
    public function render(): Factory|View|Application|Htmlable
    {
        return view('panel::components.form.date');
    }
}

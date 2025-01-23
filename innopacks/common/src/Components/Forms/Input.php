<?php

namespace InnoShop\Common\Components\Forms;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Class Input
 * @package InnoShop\Common\Components\Forms
 * @author 华锐
 * @desc 表单输入框组件，用于生成表单中的输入框
 */
class Input extends Component
{
    /**
     * 公共属性，用于存储输入框的名称
     *
     * @var string
     */
    public string $name;

    /**
     * 公共属性，用于存储输入框的标题
     *
     * @var string
     */
    public string $title;

    /**
     * 公共属性，用于存储输入框的错误信息
     *
     * @var string
     */
    public string $error;

    /**
     * 公共属性，用于存储输入框的占位符文本
     *
     * @var string
     */
    public string $placeholder;

    /**
     * 公共属性，用于存储输入框的描述信息
     *
     * @var string
     */
    public string $description;

    /**
     * 公共属性，用于存储输入框的类型，如text、password等
     *
     * @var string
     */
    public string $type;

    /**
     * 公共属性，用于指示输入框是否为必填项
     *
     * @var bool
     */
    public bool $required;

    /**
     * 公共属性，用于指示输入框是否被禁用
     *
     * @var bool
     */
    public bool $disabled;

    /**
     * 公共属性，用于指示输入框是否为只读
     *
     * @var bool
     */
    public bool $readonly;

    /**
     * 公共属性，用于存储输入框的值
     *
     * @var mixed|string
     */
    public mixed $value;

    /**
     * 公共属性，用于指示输入框是否允许多选
     *
     * @var bool
     */
    public bool $multiple;

    /**
     * 公共属性，用于存储输入框所在的列信息
     *
     * @var string
     */
    public string $column;

    /**
     * 公共属性，用于指示是否生成输入框
     *
     * @var bool
     */
    public bool $generate;

    /**
     * 构造函数，用于初始化输入框组件的属性
     *
     * @param string $name 输入框的名称
     * @param string $title 输入框的标题
     * @param mixed|null $value 输入框的值，默认为null
     * @param bool $required 输入框是否为必填项，默认为false
     * @param string $error 输入框的错误信息，默认为空字符串
     * @param string $type 输入框的类型，默认为'text'
     * @param string $placeholder 输入框的占位符文本，默认为空字符串
     * @param string $description 输入框的描述信息，默认为空字符串
     * @param bool $disabled 输入框是否被禁用，默认为false
     * @param bool $readonly 输入框是否为只读，默认为false
     * @param bool $multiple 输入框是否允许多选，默认为false
     * @param string $column 输入框所在的列信息，默认为空字符串
     * @param bool $generate 是否生成输入框，默认为false
     */
    public function __construct(
        string $name,
        string $title,
        mixed  $value = null,
        bool   $required = false,
        string $error = '',
        string $type = 'text',
        string $placeholder = '',
        string $description = '',
        bool   $disabled = false,
        bool   $readonly = false,
        bool   $multiple = false,
        string $column = '',
        bool   $generate = false
    )
    {
        // 如果不是多选输入框，则对值进行HTML实体解码
        if (!$multiple) {
            $value = html_entity_decode($value, ENT_QUOTES);
        }

        // 初始化输入框组件的属性
        $this->name        = $name;
        $this->title       = $title;
        $this->value       = $value;
        $this->error       = $error;
        $this->placeholder = $placeholder;
        $this->type        = $type;
        $this->required    = $required;
        $this->description = $description;
        $this->disabled    = $disabled;
        $this->readonly    = $readonly;
        $this->multiple    = $multiple;
        $this->column      = $column;
        $this->generate    = $generate;
    }

    /**
     * 渲染方法，用于返回视图组件的视图文件
     * @return View 渲染的视图
     */
    public function render(): View
    {
        // 返回视图文件路径，用于渲染输入框组件
        return view('panel::components.form.input');
    }
}

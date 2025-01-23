<?php

namespace InnoShop\Common\Components\Forms;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Class Textarea
 * @package InnoShop\Common\Components\Forms
 * @author 华锐
 * @desc 文本域组件，用于生成HTML文本域输入框
 */
class Textarea extends Component
{
    /**
     * 文本域名称
     *
     * @var string
     */
    public string $name;

    /**
     * 文本域标题
     *
     * @var string
     */
    public string $title;

    /**
     * 是否必填
     *
     * @var bool
     */
    public bool $required;

    /**
     * 文本域的值
     *
     * @var mixed
     */
    public mixed $value;

    /**
     * 是否允许多行输入
     *
     * @var bool
     */
    public bool $multiple;

    /**
     * 数据库列名
     *
     * @var string
     */
    public string $column;

    /**
     * 是否生成
     *
     * @var bool
     */
    public bool $generate;

    /**
     * 构造函数，初始化文本域组件的属性
     *
     * @param string $name 文本域名称
     * @param string $title 文本域标题
     * @param mixed|null $value 文本域的值，默认为null
     * @param bool $required 是否必填，默认为false
     * @param bool $multiple 是否允许多行输入，默认为false
     * @param string $column 数据库列名，默认为空字符串
     * @param bool $generate 是否生成，默认为false
     */
    public function __construct
    (
        string $name,
        string $title,
        mixed  $value = null,
        bool   $required = false,
        bool   $multiple = false,
        string $column = '',
        bool   $generate = false
    )
    {
        // 如果不是多行输入，则对值进行HTML实体解码
        if (!$multiple) {
            $value = html_entity_decode($value, ENT_QUOTES);
        }

        // 初始化属性
        $this->name     = $name;
        $this->title    = $title;
        $this->required = $required;
        $this->value    = $value;
        $this->multiple = $multiple;
        $this->column   = $column;
        $this->generate = $generate;
    }

    /**
     * 渲染视图组件
     *
     * @return View 渲染后的视图
     */
    public function render(): View
    {
        return view('panel::components.form.textarea');
    }
}

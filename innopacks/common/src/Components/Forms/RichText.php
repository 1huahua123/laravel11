<?php

namespace InnoShop\Common\Components\Forms;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Class RichText
 * @package InnoShop\Common\Components\Forms
 * @author 华锐
 * @desc 富文本编辑器组件类
 */
class RichText extends Component
{
    /**
     * 公共属性，用于存储表单字段的名称，可以为空
     *
     * @var string|null
     */
    public ?string $name;

    /**
     * 公共属性，用于存储表单字段的标题，可以为空
     *
     * @var string|null
     */
    public ?string $title;

    /**
     * 公共属性，用于存储表单字段的值，类型为混合型（可以是任何类型）
     *
     * @var mixed|string
     */
    public mixed $value;

    /**
     * 公共属性，用于标识表单字段是否为必填项，类型为布尔型
     *
     * @var bool
     */
    public bool $required;

    /**
     * 公共属性，用于标识表单字段是否允许多选，类型为布尔型
     *
     * @var bool
     */
    public bool $multiple;

    /**
     * 构造函数，用于初始化 RichText 组件
     *
     * @param string $name 表单字段的名称
     * @param string $title 表单字段的标题，默认为空字符串
     * @param bool $required 表单字段是否为必填项，默认为 false
     * @param mixed $value 表单字段的值，默认为 null
     * @param bool $multiple 表单字段是否允许多选，默认为 false
     */
    public function __construct(string $name, string $title = '', bool $required = false, mixed $value = null, bool $multiple = false)
    {
        // 如果不是多选，则对值进行 HTML 实体解码
        if (!$multiple) {
            $value = html_entity_decode($value, ENT_QUOTES);
        }

        // 初始化属性
        $this->name     = $name;
        $this->title    = $title;
        $this->value    = $value;
        $this->required = $required;
        $this->multiple = $multiple;
    }

    /**
     * 渲染方法，用于返回视图组件的视图文件
     * @return View 渲染的视图
     */
    public function render(): View
    {
        return view('panel::components.form.rich-text');
    }
}

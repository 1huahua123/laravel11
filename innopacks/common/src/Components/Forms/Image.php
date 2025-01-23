<?php

namespace InnoShop\Common\Components\Forms;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\View\Component;

/**
 * Class Image
 * @package InnoShop\Common\Components\Forms
 * @author 华锐
 * @desc 用于表单中的图片组件
 */
class Image extends Component
{
    /**
     * 图片的名称
     *
     * @var string
     */
    public string $name;

    /**
     * 图片的标题
     *
     * @var string
     */
    public string $title;

    /**
     * 图片的类型
     *
     * @var string
     */
    public string $type;

    /**
     * 图片的值（可能是URL或其他标识）
     *
     * @var string
     */
    public string $value;

    /**
     * 图片的描述信息
     *
     * @var string
     */
    public string $description;

    /**
     * 构造函数，初始化图片组件属性
     *
     * @param string $name 图片的名称
     * @param string|null $title 图片的标题
     * @param string|null $value 图片的值
     * @param string|null $description 图片的描述信息
     * @param string $type 图片的类型
     */
    public function __construct
    (
        string  $name,
        ?string $title,
        ?string $value,
        ?string $description = '',
        string  $type = 'common'
    )
    {
        $this->name        = $name;
        $this->title       = $title ?? '';
        $this->value       = $value ?? '';
        $this->description = $description ?? '';
        $this->type        = $type;
    }

    /**
     * 渲染图片组件
     *
     * @return Htmlable|Factory|View|Application
     */
    public function render(): Factory|View|Application|Htmlable
    {
        return view('panel::components.form.image');
    }
}

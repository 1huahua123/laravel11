<?php

namespace InnoShop\Common\Components\Forms;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\View\Component;

/**
 * Class Images
 * @package InnoShop\Common\Components\Forms
 * @author 华锐
 * @desc 用于表单中的多图片组件
 */
class Images extends Component
{
    /**
     * 多图片组件名称
     *
     * @var string
     */
    public string $name;

    /**
     * 多图片组件标题
     *
     * @var string
     */
    public string $title;

    /**
     * 多图片组件类型
     *
     * @var string
     */
    public string $type;

    /**
     * 多图片组件值
     *
     * @var array|mixed
     */
    public array $values;

    /**
     * 多图片组件图片最大数量
     *
     * @var int
     */
    public int $max;

    /**
     * 构造函数，初始化多图片组件属性
     *
     * @param string $name 多图片组件名称
     * @param string|null $title 多图片组件标题
     * @param int $max 多图片组件图片最大数量
     * @param string $type 多图片组件类型
     * @param array $values - 多图片组件值
     */
    public function __construct(string $name, ?string $title, int $max = 0, string $type = 'common', array $values = [])
    {
        $this->name   = $name;
        $this->values = $values;
        $this->max    = $max;
        $this->type   = $type;
        $this->title  = $title ?? '';
    }

    /**
     * 渲染多图片组件
     *
     * @return Htmlable|Factory|View|Application
     */
    public function render(): Factory|View|Application|Htmlable
    {
        return view('panel::components.form.images');
    }
}

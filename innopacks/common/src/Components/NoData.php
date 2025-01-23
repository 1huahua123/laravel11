<?php

namespace InnoShop\Common\Components;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\View\Component;

/**
 * Class NoData
 * @package InnoShop\Common\Components
 * @author 华锐
 * @desc 无数据组件，用于列表无数据时展示
 */
class NoData extends Component
{
    /**
     * 提示文本
     *
     * @var string|null
     */
    public ?string $text;

    /**
     * 组件的宽度
     *
     * @var string|null
     */
    public ?string $width;

    /**
     * 构造函数，初始化组件的文本和宽度
     *
     * @param string|null $text
     * @param string|null $width
     */
    public function __construct(?string $text = '', ?string $width = '300')
    {
        $this->text = $text;
        $this->width = $width;
    }

    /**
     * 渲染组件视图
     *
     * @return Application|Htmlable|Factory|View
     */
    public function render(): Application|Factory|View|Htmlable
    {
        return view('panel::components.no-data');
    }
}

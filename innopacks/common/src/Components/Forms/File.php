<?php

namespace InnoShop\Common\Components\Forms;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\View\Component;

/**
 * Class File
 * @package InnoShop\Common\Components\Forms
 * @author 华锐
 * @desc 文件上传组件
 */
class File extends Component
{
    /**
     * 文件上传组件名称
     *
     * @var string
     */
    public string $name;

    /**
     * 文件上传组件标题
     *
     * @var string
     */
    public string $title;

    /**
     * 文件上传组件类型
     *
     * @var string
     */
    public string $type;

    /**
     * 文件上传组件文件类型
     *
     * @var string
     */
    public string $fileType;

    /**
     * 文件上传组件默认值
     *
     * @var string
     */
    public string $value;

    /**
     * 文件上传组件的描述
     *
     * @var string
     */
    public string $description;

    /**
     * 构造函数，初始化文件上传组件的属性
     *
     * @param string $name 文件上传组件名称
     * @param string|null $title 文件上传组件标题
     * @param string|null $value 文件上传组件默认值
     * @param string|null $description 文件上传组件的描述
     * @param string $type 文件上传组件类型
     * @param string $fileType 文件上传组件文件类型
     */
    public function __construct
    (
        string  $name,
        ?string $title,
        ?string $value,
        ?string $description = '',
        string  $type = 'common',
        string  $fileType = 'zip'
    )
    {
        $this->name        = $name;
        $this->title       = $title ?? '';
        $this->value       = $value ?? '';
        $this->description = $description ?? '';
        $this->type        = $type;
        $this->fileType    = $fileType;
    }

    /**
     * 渲染文件上传组件
     *
     * @return Htmlable|Factory|View|Application
     */
    public function render(): Factory|View|Application|Htmlable
    {
        $data['accept'] = match ($this->fileType) {
            'zip' => '.zip',
            'pdf' => '.pdf',
            'doc' => '.doc, .docx',
            'xls' => '.xls, .xlsx',
            'ppt' => '.ppt, .pptx',
            'img' => '.jpg, .jpeg, .png, .gif',
            default => '.zip',
        };

        return view('panel::components.form.file', $data);
    }
}

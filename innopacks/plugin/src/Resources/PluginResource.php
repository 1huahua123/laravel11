<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/20 21:42
 * @Project    : laravel11
 * @Description: 插件资源类，用于将插件数据转换为JSON格式
 */

namespace InnoShop\Plugin\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PluginResource extends JsonResource
{
    /**
     * 将插件资源转换为数组形式，以便于JSON响应
     *
     * @param Request $request 当前HTTP请求实例
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            // 插件的代码标识
            'code'        => $this->getCode(),
            // 插件的本地化名称
            'name'        => $this->getLocaleName(),
            // 插件的本地化描述
            'description' => $this->getLocaleDescription(),
            // 插件的路径
            'path'        => $this->getPath(),
            // 插件的版本号
            'version'     => $this->getVersion(),
            // 插件的优先级
            'priority'    => $this->getPriority(),
            // 插件的目录名称
            'dir_name'    => $this->getDirName(),
            // 插件的类型
            'type'        => $this->getType(),
            // 插件的作者
            'author'      => $this->getAuthor(),
            // 插件是否启用
            'enabled'     => $this->getEnabled(),
            // 插件是否已安装
            'installed'   => $this->checkInstalled(),
            // 插件的编辑URL
            'edit_url'    => $this->getEditUrl(),
            // 插件的图标，经过尺寸调整
            'icon'        => plugin_resize($this->getCode(), $this->getIcon()),
            // 插件类型的本地化格式
            'type_format' => panel_trans('plugin.' . $this->getType()),
        ];
    }
}

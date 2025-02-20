<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/20 22:39
 * @Project    : laravel11
 * @Description: 提供了一系列方法用于处理图像，包括设置插件目录名称、调整图像大小以及获取图像的 URL
 */

namespace InnoShop\Common\Services;

use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageService
{
    /**
     * @var string 原始图像路径
     */
    private string $originImage;

    /**
     * @var string|mixed 当前图像路径
     */
    private string $image;

    /**
     * @var string 图像存储路径
     */
    private string $imagePath;

    /**
     * @var string|mixed 占位图像路径
     */
    private string $placeholderImage;

    /**
     * 占位图像的常量路径
     */
    const PLACEHOLDER_IMAGE = 'images/placeholder.png';

    /**
     * 构造函数
     * @param $image - 图像路径
     */
    public function __construct($image)
    {
        $this->originImage = $image;
        // 获取系统设置的占位图像路径，如果没有设置则使用默认的占位图像
        $this->placeholderImage = system_setting('placeholder', self::PLACEHOLDER_IMAGE);
        // 检查占位图像是否存在，如果不存在则使用默认的占位图像
        if (!is_file(public_path($this->placeholderImage))) {
            $this->placeholderImage = self::PLACEHOLDER_IMAGE;
        }
        // 设置当前图像路径，如果传入的图像路径为空则使用占位图像
        $this->image = $image ?: $this->placeholderImage;
        // 获取当前图像的完整路径
        $this->imagePath = public_path($this->image);
        // 检查当前图像是否存在，如果不存在则使用占位图像
        if (!is_file($this->imagePath)) {
            $this->image = $this->placeholderImage;
            $this->imagePath = public_path($this->placeholderImage);
        }
    }

    /**
     * 获取ImageService实例
     * @param $image - 图像路径
     * @return self
     */
    public static function getInstance($image): self
    {
        return new self($image);
    }

    /**
     * 设置插件目录名称
     * @param $dirName - 插件目录名称
     * @return $this
     */
    public function setPluginDirName($dirName): static
    {
        $originImage = $this->originImage;
        // 设置插件目录下的图像路径
        $this->imagePath = plugin_path("{$dirName}/Public") . $originImage;
        // 检查插件目录下的图像是否存在，如果存在则设置当前图像路径，否则使用占位图像
        if (file_exists($this->imagePath)) {
            $this->image = strtolower('plugins/' . $dirName . $originImage);
        } else {
            $this->image = $this->placeholderImage;
            $this->imagePath = public_path($this->image);
        }

        return $this;
    }

    /**
     * 调整图像大小
     * @param int $width 目标宽度
     * @param int $height 目标高度
     * @return string 调整后的图像路径
     */
    public function resize(int $width = 100, int $height = 100): string
    {
        try {
            // 获取图像扩展名
            $extension = pathinfo($this->imagePath, PATHINFO_EXTENSION);
            // 生成新的图像路径
            $newImage = 'cache/' . mb_substr($this->image, 0, mb_strrpos($this->image, '.')) . '-' . $width . 'x' . $height . '.' . $extension;

            $newImagePath = public_path($newImage);
            // 检查新图像是否存在，或者原图像是否比新图像更新，如果是则重新生成新图像
            if (!is_file($newImagePath) || (filemtime($this->imagePath) > filemtime($newImagePath))) {
                create_directories(dirname($newImagePath));

                $manager = new ImageManager(new Driver);
                $image = $manager->read($this->imagePath);

                $image->cover($width, $height);
                $image->save($newImagePath);
            }

            return assert($newImage);
        } catch (\Exception $e) {
            // 记录错误日志
            Log::error($e->getMessage());

            // 返回原始图像URL
            return $this->originUrl();
        }
    }

    /**
     * 获取原始图像URL
     * @return string 原始图像URL
     */
    public function originUrl(): string
    {
        return asset($this->image);
    }
}

<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/16 17:45
 * @Project    : laravel11
 * @Description: 插件管理器类，用于管理插件的安装、激活、导入等操作
 */

namespace InnoShop\Plugin\Core;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PhpZip\Exception\ZipException;
use PhpZip\ZipFile;

class PluginManager
{
    // 静态属性，用于存储已安装的插件集合
    protected static ?Collection $plugins = null;

    /**
     * 获取所有插件
     * @return Collection|null 返回插件集合，如果未初始化则返回null
     */
    public function getPlugins()
    {
        // 如果插件集合已初始化，则直接返回
        if (self::$plugins !== null) {
            return self::$plugins;
        }

        // 如果插件集合未初始化，则返回null
        // 注意：这里缺少初始化插件集合的代码，需要补充
    }

    /**
     * 检查插件是否激活
     * @param string $code 插件代码
     * @return bool 返回插件是否激活
     */
    public function checkActive(string $code): bool
    {
        // 获取插件实例
        $plugin = $this->getPlugin($code);
        // 检查插件是否存在、已安装且已启用
        if (empty($plugin) || !$plugin->checkInstalled() || !$plugin->getEnabled()) {
            return false;
        }

        // 插件激活返回true
        return true;
    }

    /**
     * 根据代码获取插件实例
     * @param string $code 插件代码
     * @return mixed|null 返回插件实例，如果不存在则返回null
     */
    public function getPlugin(string $code): mixed
    {
        // 将代码转换为蛇形命名
        $code    = Str::snake($code);
        // 获取所有插件
        $plugins = $this->getPlugins();

        // 返回指定代码的插件实例，如果不存在则返回null
        return $plugins[$code] ?? null;
    }

    /**
     * 获取插件配置信息
     * @return array 返回已安装的插件配置信息
     */
    protected function getPluginsConfig(): array
    {
        $installed = [];
        // 打开插件目录
        $resource  = opendir($this->getPluginsDir());
        while ($filename = @readdir($resource)) {
            // 跳过当前目录和上级目录
            if ($filename == '.' || $filename == '..') {
                continue;
            }
            $path = $this->getPluginsDir() . DIRECTORY_SEPARATOR . $filename;
            // 检查是否为目录
            if (is_dir($path)) {
                $packageJsonPath = $path . DIRECTORY_SEPARATOR . 'config.json';
                // 检查配置文件是否存在
                if (file_exists($packageJsonPath)) {
                    // 读取配置文件并解析为数组
                    $installed[$filename] = json_decode(file_get_contents($packageJsonPath), true);
                }
            }
        }
        // 关闭目录资源
        closedir($resource);

        // 返回已安装的插件配置信息
        return $installed;
    }

    /**
     * 获取插件目录路径
     * @return string 返回插件目录路径
     */
    protected function getPluginsDir(): string
    {
        // 从配置文件获取插件目录，如果未设置则使用默认路径
        return config('plugins.directory') ?: base_path('plugins');
    }

    /**
     * 导入插件
     * @param UploadedFile $file 上传的文件
     * @throws ZipException
     */
    public function import(UploadedFile $file): void
    {
        // 获取文件原始名称
        $originalName = $file->getClientOriginalName();
        // 设置文件存储路径
        $destPath     = storage_path('upload');
        $newFilePath  = $destPath . '/' . $originalName;
        // 移动文件到指定路径
        $file->move($destPath, $originalName);

        // 创建ZipFile实例
        $zipFile = new ZipFile();
        // 打开文件并解压到插件目录
        $zipFile->openFile($newFilePath)->extractTo(base_path('plugins'));
    }
}

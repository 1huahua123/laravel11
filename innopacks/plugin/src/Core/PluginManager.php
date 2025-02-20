<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/16 17:45
 * @Project    : laravel11
 * @Description: 插件管理器类，用于管理插件的安装、激活、导入等操作
 */

namespace InnoShop\Plugin\Core;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
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
     * @throws Exception
     */
    public function getPlugins(): ?Collection
    {
        // 如果插件集合已初始化，则直接返回
        if (self::$plugins !== null) {
            return self::$plugins;
        }

        // 获取已存在的插件配置
        $existed = $this->getPluginsConfig();
        // 创建一个新的插件集合
        $plugins = new Collection();
        // 遍历已存在的插件配置
        foreach ($existed as $dirname => $package) {
            // 如果插件被标记为隐藏，则跳过
            if ($package['hide'] ?? false) {
                continue;
            }

            // 获取插件的路径
            $pluginPath = $this->getPluginsDir() . DIRECTORY_SEPARATOR . $dirname;

            try {
                // 尝试创建插件实例
                $plugin = new Plugin($pluginPath, $package);
            } catch (Exception $e) {
                // 如果创建插件实例失败，记录错误日志并跳过
                Log::error('The Plugin: ' . $dirname . ' - ' . $e->getMessage());

                continue;
            }

            // 设置插件的各项属性
            $plugin->setCode($package['code']);
            $plugin->setType($package['type']);
            $plugin->setName($package['name']);
            $plugin->setDescription($package['description']);
            $plugin->setAuthor($package['author']['name'] . '(' . $package['author']['email'] . ')');
            $plugin->setIcon($package['icon']);
            $plugin->setVersion($package['version']);
            $plugin->setDirname($dirname);
            $plugin->setInstalled(true);
            $plugin->setEnabled($plugin->checkActive());
            $plugin->setPriority($plugin->checkPriority());
            $plugin->setFields();

            // 获取插件的代码
            $code = $plugin->getCode();
            // 如果插件集合中已存在相同代码的插件，则跳过
            if ($plugins->has($code)) {
                continue;
            }
            // 将插件添加到插件集合中
            $plugins->put($code, $plugin);
        }

        // 按照优先级对插件集合进行排序
        self::$plugins =$plugins->sortBy(function ($plugin) {
            return $plugin->getPriority();
        });

        // 返回排序后的插件集合
        return self::$plugins;
    }

    /**
     * 获取所有已启用插件的集合
     *
     * 该方法首先获取所有插件，然后通过过滤器筛选出已安装且已启用的插件。
     *
     * @return Collection 返回一个包含所有已启用插件的集合
     * @throws Exception 如果在获取插件或筛选过程中发生错误，将抛出异常
     */
    public function getEnabledPlugins(): Collection
    {
        // 获取所有插件
        $allPlugins = $this->getPlugins();

        // 使用过滤器筛选出已安装且已启用的插件
        return $allPlugins->filter(function (Plugin $plugin) {
            // 检查插件是否已安装且已启用
            return $plugin->checkInstalled() && $plugin->getEnabled();
        });
    }

    /**
     * @param $code
     * @return Plugin|null
     * @throws Exception
     */
    public function getPluginOrFail($code): ?Plugin
    {
        $plugin = $this->getPlugin($code);
        if (empty($plugin)) {
            throw new Exception('Invalid plugin!');
        }
        $plugin->handleLabel();

        return $plugin;
    }

    /**
     * 检查插件是否激活
     * @param string $code 插件代码
     * @return bool 返回插件是否激活
     * @throws Exception
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
     * @return Plugin|null 返回插件实例，如果不存在则返回null
     * @throws Exception
     */
    public function getPlugin(string $code): ?Plugin
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

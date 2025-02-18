<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/16 18:01
 * @Project    : laravel11
 * @Description: 插件管理
 */

namespace InnoShop\Plugin\Core;

use ArrayAccess;
use Exception;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use InnoShop\Plugin\Repositories\PluginRepo;
use InnoShop\Plugin\Repositories\SettingRepo;

final class Plugin implements Arrayable, ArrayAccess
{
    // 定义插件类型的常量数组
    public const TYPES = [
        'billing',
        'shipping',
        'feature',
        'fee',
        'social',
        'language',
        'intelli'
    ];

    // 定义属性，用于存储插件类型
    protected string $type;

    // 定义属性，用于存储插件路径
    protected string $path;

    // 定义属性，用于存储插件代码
    protected string $code;

    // 定义属性，用于存储插件图标
    protected string $icon;

    // 定义属性，用于存储插件作者
    protected string $author;

    // 定义属性，用于存储插件名称
    protected array|string $name;

    // 定义属性，用于存储插件描述
    protected array|string $description;

    // 定义属性，用于存储插件包信息
    protected array $packageInfo;

    // 定义属性，用于存储插件目录名
    protected string $dirName;

    // 定义属性，用于存储插件是否已安装
    protected bool $installed;

    // 定义属性，用于存储插件是否已启用
    protected bool $enabled;

    // 定义属性，用于存储插件优先级
    protected int $priority;

    // 定义属性，用于存储插件版本
    protected string $version;

    // 定义属性，用于存储插件字段信息
    protected array $fields = [];

    // 构造函数，初始化插件路径和包信息
    public function __construct(string $path, array $packageInfo)
    {
        $this->path = $path;
        $this->packageInfo = $packageInfo;
        $this->validateConfig();
    }

    // 获取包信息中的属性值
    public function __get($name)
    {
        return $this->packageInfoAttribute(Str::snake($name, '-'));
    }

    // 设置插件类型
    public function setType(string $type): Plugin
    {
        if (!in_array($type, self::TYPES)) {
            throw new Exception('Invalid plugin type, must be one of ' . implode(',', self::TYPES));
        }
        $this->type = $type;

        return $this;
    }

    // 设置插件目录名
    public function setDirname(string $dirName): Plugin
    {
        $this->dirName = $dirName;

        return $this;
    }

    // 设置插件代码
    public function setCode(string $code): Plugin
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @param string|array $name
     * @return $this
     */
    public function setName(string|array $name): Plugin
    {
        $this->name = $name;

        return $this;
    }

    /**

     * 设置插件的描述信息。
     *
     * @param string|array $description 描述信息，可以是字符串或数组。
     * @return $this 返回当前对象实例，以支持链式调用。
     */
    public function setDescription(string|array $description): Plugin
    {
        // 将传入的描述信息赋值给对象的description属性。
        $this->description = $description;

        // 返回当前对象实例，以便可以继续进行链式调用。
        return $this;
    }

    /**
     * 设置插件的图标
     *
     * @param string $icon 图标字符串，用于指定插件的图标
     * @return $this 返回当前对象实例，以便支持链式调用
     */
    public function setIcon(string $icon): Plugin
    {
        // 将传入的图标字符串赋值给当前对象的icon属性
        $this->icon = $icon;

        // 返回当前对象实例，以便可以继续调用其他方法
        return $this;
    }

    /**
     * 设置插件的作者
     *
     * @param string $author 插件作者的名称
     * @return $this 返回当前对象实例，以支持链式调用
     */
    public function setAuthor(string $author): Plugin
    {
        // 将传入的作者名称赋值给当前对象的author属性
        $this->author = $author;

        // 返回当前对象实例，以便可以继续进行链式调用
        return $this;
    }

    /**
     * 设置插件是否已安装的方法
     *
     * @param bool $installed 一个布尔值，表示插件是否已安装
     * @return $this 返回当前对象实例，以便支持链式调用
     */
    public function setInstalled(bool $installed): Plugin
    {
        // 将传入的布尔值赋值给当前对象的installed属性
        $this->installed = $installed;

        // 返回当前对象实例，以便可以继续调用其他方法
        return $this;
    }

    /**
     * 设置插件是否启用
     *
     * 该方法接收一个布尔值参数，用于设置插件的启用状态。
     * 设置完成后，返回当前对象实例，以便进行链式调用。
     *
     * @param bool $enabled 插件是否启用，true为启用，false为禁用
     * @return $this 返回当前对象实例，支持链式调用
     */
    public function setEnabled(bool $enabled): Plugin
    {
        // 将传入的布尔值赋值给当前对象的enabled属性
        $this->enabled = $enabled;

        // 返回当前对象实例，以便进行链式调用
        return $this;
    }

    /**
     * 设置优先级的方法
     *
     * @param int $priority 要设置的优先级值，类型为整数
     * @return $this 返回当前对象实例，以便支持链式调用
     */
    public function setPriority(int $priority): Plugin
    {
        // 将传入的优先级值赋给当前对象的priority属性
        $this->priority = $priority;

        // 返回当前对象实例，以便可以继续调用其他方法
        return $this;
    }

    /**
     * 设置版本号
     *
     * @param string $version 要设置的版本号，类型为字符串
     * @return $this 返回当前对象实例，以便支持链式调用
     */
    public function setVersion(string $version): Plugin
    {
        // 将传入的版本号赋值给对象的版本属性
        $this->version = $version;

        // 返回当前对象实例，以便可以继续调用其他方法
        return $this;
    }

    /**
     * 设置字段数据的方法
     *
     * 该方法用于从指定的路径加载字段数据，并将其存储在当前对象的fields属性中。
     * 如果指定的文件不存在，则直接返回当前对象。
     * 如果文件存在且内容为数组且不为空，则将文件内容赋值给当前对象的fields属性。
     *
     * @return $this 当前对象实例，以便支持链式调用
     */
    public function setFields(): Plugin
    {
        // 构建字段配置文件的完整路径
        $fieldsPath = $this->path . DIRECTORY_SEPARATOR . 'fields.php';
        // 检查字段配置文件是否存在
        if (!file_exists($fieldsPath)) {
            // 如果文件不存在，直接返回当前对象
            return $this;
        }

        // 引入字段配置文件，并获取其内容
        $fieldsData = require_once $fieldsPath;
        // 检查获取到的内容是否为数组且不为空
        if (is_array($fieldsData) && $fieldsData) {
            // 如果是数组且不为空，则将其赋值给当前对象的fields属性
            $this->fields = $fieldsData;
        }

        // 返回当前对象实例，以便支持链式调用
        return $this;
    }

    /**
     * 获取对象的名称属性。
     *
     * 该方法用于返回对象的名称属性。返回值的类型可以是数组或字符串，
     * 具体取决于属性 $name 的类型。
     *
     * @return array|string 返回对象的名称属性，类型为数组或字符串。
     */
    public function getName(): array|string
    {
        return $this->name;
    }

    /**
     * 获取当前区域设置的语言名称。
     *
     * @return mixed|string 返回当前区域设置的语言名称，如果不存在则返回默认名称。
     */
    public function getLocaleName(): mixed
    {
        // 获取当前插件的语言代码
        $currentLocale = plugin_locale_code();

        // 检查$name属性是否是一个数组
        if (is_array($this->name)) {
            // 如果当前语言代码存在于$name数组中，则返回对应的语言名称
            if ($this->name[$currentLocale] ?? '') {
                return $this->name[$currentLocale];
            }

            // 如果当前语言代码不存在于$name数组中，则返回数组中的第一个值作为默认语言名称
            return array_values($this->name)[0];
        }

        // 如果$name属性不是数组，则直接将其转换为字符串并返回
        return (string)$this->name;
    }

    /**

     * 获取描述信息的方法
     *
     * 该方法用于返回对象的描述信息。返回类型可以是数组或字符串，
     * 具体取决于描述信息的格式。
     *
     * @return array|string 返回描述信息，可以是数组或字符串
     */
    public function getDescription(): array|string
    {
        return $this->description;
    }

    /**
     * 获取当前区域设置的描述信息。
     *
     * 该方法首先获取当前插件的区域设置代码，然后根据该代码从描述数组中获取相应的描述。
     * 如果描述是一个数组，并且当前区域设置的描述存在，则返回该描述。
     * 如果当前区域设置的描述不存在，则返回描述数组中的第一个值。
     * 如果描述不是数组，则直接返回描述的字符串表示。
     *
     * @return mixed|string 返回当前区域设置的描述信息，可能是字符串或混合类型。
     */
    public function getLocaleDescription(): mixed
    {
        $currentLocale = plugin_locale_code();
        // 获取当前插件的区域设置代码

        if (is_array($this->description)) {
        // 检查描述是否为数组
            if ($this->description[$currentLocale] ?? '') {
            // 检查当前区域设置的描述是否存在
                return $this->description[$currentLocale];
                // 返回当前区域设置的描述
            }

            return array_values($this->description)[0];
            // 如果当前区域设置的描述不存在，返回描述数组中的第一个值
        }

        // 如果描述不是数组，直接返回描述的字符串表示
        return (string)$this->description;
    }

    /**

     * 获取代码的函数
     *
     * 该函数用于返回对象的代码属性值。
     *
     * @return string 返回一个字符串类型的代码值。
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * 获取目录名称的方法
     *
     * 该方法用于返回对象的目录名称属性值。
     *
     * @return string 返回一个字符串，表示目录名称
     */
    public function getDirname(): string
    {
        return $this->dirName;
    }

    /**
     * 获取对象的 icon 属性值
     *
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * 获取对象的作者属性
     *
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * 获取编辑插件的URL
     *
     * @return string
     */
    public function getEditUrl(): string
    {
        // 获取当前插件的视图文件路径
        $viewFile = $this->getPath() . '/Views/panel/config.blade.php';
        // 检查以下条件：
        // 1. $this->fields 是否为空
        // 2. 视图文件是否存在
        // 3. 插件类型是否不是 'billing'
        // 如果所有条件都满足，则返回空字符串
        if (empty($this->fields) && !file_exists($viewFile) && $this->type != 'billing') {
            return '';
        }

        // 如果上述条件不满足，则生成并返回编辑插件的URL
        // 使用 panel_route 函数生成路由，路由名称为 'plugins.edit'
        // 并传递插件代码作为参数
        return panel_route('plugins.edit', ['plugin' => $this->code]);
    }

    /**
     * 检查当前插件是否处于激活状态
     *
     * 该方法通过调用PluginRepo类的静态方法getInstance获取PluginRepo的单例实例，
     * 然后调用该实例的checkActive方法，传入当前对象的code属性作为参数，
     * 用于检查该插件代码对应的插件是否激活。
     *
     * @return bool 返回插件是否激活的布尔值
     */
    public function checkActive(): bool
    {
        // 获取PluginRepo的单例实例
        return PluginRepo::getInstance()->checkActive($this->code);
    }

    /**
     * 检查插件是否已安装
     *
     * 该方法用于检查当前插件是否已经安装。通过调用PluginRepo类的installed方法，
     * 并传入当前插件的代码（$this->code），来判断插件是否已安装。
     *
     * @return bool 返回一个布尔值，表示插件是否已安装。如果已安装，返回true；否则返回false。
     */
    public function checkInstalled(): bool
    {
        return PluginRepo::getInstance()->installed($this->code);
    }

    /**
     *
     *
     * @return int
     */
    public function checkPriority(): int
    {
        return PluginRepo::getInstance()->getPriority($this->code);
    }

    /**
     * @return bool
     */
    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getSetting(string $key = ''): mixed
    {
        if ($key) {
            return plugin_setting($this->code, $key);
        }

        return plugin_setting($this->code);
    }

    /**
     * @return void
     */
    public function handleLabel(): void
    {
        $this->fields = collect($this->fields)->map(function ($item) {
            $item = $this->transLabel($item);
            if (isset($item['options'])) {
                $item['options'] = collect($item['options'])->map(function ($option) {
                    return $this->transLabel($option);
                })->toArray();
            }

            return $item;
        })->toArray();
    }

    /**
     * @return string
     */
    public function getFieldView(): string
    {
        $viewFile = $this->getPath() . '/Views/panel/config.blade.php';
        if (file_exists($viewFile)) {
            return "{$this->dirName}::panel.config";
        }

        return '';
    }

    /**
     * @return string
     */
    public function getBootFile(): string
    {
        return $this->getPath() . '/Boot.php';
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return void
     */
    public function validateConfig(): void
    {
        Validator::validate($this->packageInfo, [
            'type' => 'required',
            'name' => 'required',
            'description' => 'required',
            'code' => 'required|string|min:3|max:64',
            'version' => 'required|string'
        ]);
    }

    /**
     * @param $requestData
     * @return \Illuminate\Validation\Validator
     */
    public function validateFields($requestData): \Illuminate\Validation\Validator
    {
        $rules = array_column($this->getFields(), 'rules', 'name');

        return Validator::make($requestData, $rules);
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        if ($this->getType() == 'billing') {
            $this->fields[] = SettingRepo::getInstance()->getPluginActiveField();
        }

        $this->fields[] = SettingRepo::getInstance()->getPluginActiveField();
        $existValues = SettingRepo::getInstance()->getPluginFields($this->code);
        foreach ($this->fields as $index => $field) {
            $dbField = $existValues[$field['name']] ?? null;
            $value = $dbField ? $dbField->value : null;
            if ($field['name'] == 'active') {
                $value = (int)$value;
            }
            $this->fields[$index]['value'] = $value;
        }

        return $this->fields;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array|array[]|string[]
     */
    public function toArray(): array
    {
        return array_merge([
            'name' => $this->name,
            'version' => $this->getVersion(),
            'path' => $this->path
        ], $this->packageInfo);
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param $item
     * @return mixed
     */
    private function transLabel($item): mixed
    {
        $labelKey = $item['label_key'] ?? '';
        $label = $item['label'] ?? '';
        if (empty($label) && $labelKey) {
            $languageKey = "$this->dirName::$labelKey";
            $item['label'] = trans($labelKey);
        }

        $descriptionKey = $item['description_key'] ?? '';
        $description = $item['description'] ?? '';
        if (empty($description) && $descriptionKey) {
            $languageKey = "$this->dirName::$descriptionKey";
            $item['description'] = trans($languageKey);
        }

        return $item;
    }

    /**
     * @param $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return Arr::has($this->packageInfo, $offset);
    }

    /**
     * @param $offset
     * @return mixed
     */
    public function offsetGet($offset): mixed
    {
        return $this->packageInfoAttribute($offset);
    }

    /**
     * @param $name
     * @return array|ArrayAccess|mixed
     */
    public function packageInfoAttribute($name): mixed
    {
        return Arr::get($this->packageInfo, $name);
    }

    /**
     * @param $offset
     * @param $value
     * @return array
     */
    public function offsetSet($offset, $value): array
    {
        return Arr::set($this->packageInfo, $offset, $value);
    }

    /**
     * 取消设置数组中的某个偏移位
     *
     * @param $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->packageInfo[$offset]);
    }
}

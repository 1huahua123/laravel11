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

    /**
     * 构造函数，用于初始化对象并验证配置信息
     *
     * @param string $path 路径信息，通常是一个字符串，表示文件或目录的路径
     * @param array  $packageInfo 包信息，通常是一个关联数组，包含包的详细信息
     */
    public function __construct(string $path, array $packageInfo)
    {
        // 将传入的路径信息赋值给对象的属性 $path
        $this->path        = $path;
        // 将传入的包信息赋值给对象的属性 $packageInfo
        $this->packageInfo = $packageInfo;
        // 调用 validateConfig 方法进行配置信息的验证
        $this->validateConfig();
    }

    /**
     * 魔术方法 __get 用于访问未定义的属性
     * @param $name - 属性名
     * @return mixed 返回属性的值
     */
    public function __get($name)
    {
        // 将属性名转换为蛇形命名（例如：camelCase 转换为 camel-case）
        return $this->packageInfoAttribute(Str::snake($name, '-'));
    }

    /**
     * 设置插件类型
     * @param string $type 插件类型
     * @return $this 当前对象实例
     * @throws Exception 如果类型无效，抛出异常
     */
    public function setType(string $type): Plugin
    {
        // 检查类型是否在允许的类型列表中
        if (!in_array($type, self::TYPES)) {
            // 如果类型无效，抛出异常
            throw new Exception('Invalid plugin type, must be one of ' . implode(',', self::TYPES));
        }
        // 设置插件类型
        $this->type = $type;

        // 返回当前对象实例，允许链式调用
        return $this;
    }

    /**
     * 设置插件目录名
     * @param string $dirName 插件目录名
     * @return $this 当前对象实例
     */
    public function setDirname(string $dirName): Plugin
    {
        // 设置插件目录名
        $this->dirName = $dirName;

        // 返回当前对象实例，允许链式调用
        return $this;
    }

    /**
     * 设置插件代码
     * @param string $code 插件代码
     * @return $this 当前对象实例
     */
    public function setCode(string $code): Plugin
    {
        // 设置插件代码
        $this->code = $code;

        // 返回当前对象实例，允许链式调用
        return $this;
    }

    /**
     * 设置插件的名称。
     *
     * @param string|array $name 插件的名称，可以是字符串或数组。
     * @return $this 返回当前对象实例，以支持链式调用。
     */
    public function setName(string|array $name): Plugin
    {
        // 将传入的名称赋值给对象的name属性。
        $this->name = $name;

        // 返回当前对象实例，以便可以继续链式调用其他方法。
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
     * 检查并返回当前插件的优先级
     *
     * 该方法通过调用PluginRepo类的getInstance方法获取PluginRepo的单例实例，
     * 然后调用该实例的getPriority方法，传入当前对象的code属性作为参数，
     * 最终返回该插件的优先级值。
     *
     * @return int 返回一个整数，表示当前插件的优先级
     */
    public function checkPriority(): int
    {
        return PluginRepo::getInstance()->getPriority($this->code);
    }

    /**
     * 获取启用状态
     *
     * 该方法用于返回当前对象的启用状态。
     *
     * @return bool 返回一个布尔值，表示是否启用。
     */
    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * 获取优先级
     *
     * 该方法用于返回当前对象的优先级。
     *
     * @return int 返回一个整数，表示优先级。
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * 获取设置
     *
     * 该方法用于获取当前对象的特定设置或所有设置。
     *
     * @param string $key 设置的键名，默认为空字符串。如果提供键名，则返回对应的设置值；否则返回所有设置。
     * @return mixed 返回设置的值，类型可以是任意类型。
     */
    public function getSetting(string $key = ''): mixed
    {
        if ($key) {
            // 如果提供了键名，则返回对应的设置值
            return plugin_setting($this->code, $key);
        }

        // 如果未提供键名，则返回所有设置
        return plugin_setting($this->code);
    }

    /**
     * 处理标签的方法
     *
     * 该方法遍历字段集合，对每个字段的标签进行翻译转换。
     * 如果字段包含选项（options），则对每个选项的标签也进行翻译转换。
     * 最终将处理后的字段集合转换回数组并赋值回原字段属性。
     *
     * @return void 该方法没有返回值
     */
    public function handleLabel(): void
    {
        // 使用collect辅助函数将字段数组转换为集合
        $this->fields = collect($this->fields)->map(function ($item) {
            // 调用transLabel方法对当前字段的标签进行翻译
            $item = $this->transLabel($item);
            // 检查当前字段是否包含选项（options）
            if (isset($item['options'])) {
                // 如果有选项，则对每个选项的标签进行翻译
                $item['options'] = collect($item['options'])->map(function ($option) {
                    // 调用transLabel方法对选项的标签进行翻译
                    return $this->transLabel($option);
                })->toArray(); // 将翻译后的选项集合转换回数组
            }

            // 返回处理后的字段
            return $item;
        })->toArray(); // 将处理后的字段集合转换回数组并赋值回原字段属性
    }

    /**
     * 获取字段视图
     *
     * @return string 返回字段视图的名称，如果视图文件存在，则返回命名视图，否则返回空字符串
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
     * 获取启动文件路径
     *
     * @return string 返回启动文件的完整路径
     */
    public function getBootFile(): string
    {
        return $this->getPath() . '/Boot.php';
    }

    /**
     * 获取插件路径
     *
     * @return string 返回插件的完整路径
     */
    public function getPath(): string
    {
        return $this->path;
    }


    /**
     * 验证配置信息
     *
     * @return void
     */
    public function validateConfig(): void
    {
        // 使用Validator类验证包信息
        Validator::validate($this->packageInfo, [
            'type'        => 'required', // 类型字段必须存在
            'name'        => 'required', // 名称字段必须存在
            'description' => 'required', // 描述字段必须存在
            'code'        => 'required|string|min:3|max:64', // 代码字段必须存在，且为字符串，长度在3到64之间
            'version'     => 'required|string' // 版本字段必须存在，且为字符串
        ]);
    }

    /**
     * 验证字段
     *
     * @param array $requestData 请求数据
     * @return \Illuminate\Validation\Validator 验证器实例
     */
    public function validateFields(array $requestData): \Illuminate\Validation\Validator
    {
        // 获取字段规则
        $rules = array_column($this->getFields(), 'rules', 'name');

        // 创建验证器实例
        return Validator::make($requestData, $rules);
    }

    /**
     * 获取字段信息的方法
     * @return array 返回字段信息的数组
     */
    public function getFields(): array
    {
        // 检查当前对象的类型是否为 'billing'
        if ($this->getType() == 'billing') {
            // 如果是 'billing' 类型，添加一个插件激活字段到字段数组中
            $this->fields[] = SettingRepo::getInstance()->getPluginActiveField();
        }

        // 添加一个插件激活字段到字段数组中
        $this->fields[] = SettingRepo::getInstance()->getPluginActiveField();
        // 从SettingRepo获取当前对象代码对应的插件字段值
        $existValues = SettingRepo::getInstance()->getPluginFields($this->code);
        // 遍历字段数组
        foreach ($this->fields as $index => $field) {
            // 从数据库中获取当前字段的值，如果不存在则默认为null
            $dbField = $existValues[$field['name']] ?? null;
            // 获取字段的值，如果数据库中没有则默认为null
            $value = $dbField ? $dbField->value : null;
            // 如果字段名称为 'active'，将值转换为整数
            if ($field['name'] == 'active') {
                $value = (int)$value;
            }
            // 更新字段数组中当前字段的值为数据库中的值
            $this->fields[$index]['value'] = $value;
        }

        // 返回更新后的字段数组
        return $this->fields;
    }

    /**
     * 获取对象的类型
     *
     * 该方法用于返回对象的类型，类型以字符串形式表示。
     *
     * @return string 返回对象的类型，类型为字符串
     */
    public function getType(): string
    {
        // 返回对象的类型属性
        return $this->type;
    }

    /**
     * 将对象转换为数组形式
     *
     * 该方法将对象的属性和包信息合并成一个数组并返回。
     * 返回的数组包含对象的名称、版本、路径以及包的其他信息。
     *
     * @return array|array[]|string[] 返回一个数组，其中包含对象的属性和包信息。
     *                                数组的键为属性名，值为对应的属性值。
     */
    public function toArray(): array
    {
        // 使用array_merge函数合并两个数组
        // 第一个数组包含对象的名称、版本和路径
        // 第二个数组是对象的包信息，存储在$this->packageInfo中
        return array_merge([
            'name'    => $this->name,       // 对象的名称属性
            'version' => $this->getVersion(), // 对象的版本属性，通过getVersion方法获取
            'path'    => $this->path        // 对象的路径属性
        ], $this->packageInfo);          // 对象的包信息数组
    }

    /**
     * 获取当前对象的版本号
     *
     * 该方法用于返回对象的版本号，版本号是一个字符串类型的数据
     *
     * @return string 返回对象的版本号
     */
    public function getVersion(): string
    {
        // 返回对象的版本号属性
        return $this->version;
    }

    /**
     * 翻译菜单项的标签和描述
     *
     * @param array $item 菜单项数组，包含label_key, label, description_key, description等键
     * @return array 返回包含已翻译标签和描述的菜单项数组
     */
    private function transLabel(array $item): array
    {
        // 获取标签键和标签值，如果标签为空且标签键存在，则进行翻译
        $labelKey = $item['label_key'] ?? '';
        $label    = $item['label'] ?? '';
        if (empty($label) && $labelKey) {
            $languageKey   = "$this->dirName::$labelKey";
            $item['label'] = trans($languageKey);
        }

        // 获取描述键和描述值，如果描述为空且描述键存在，则进行翻译
        $descriptionKey = $item['description_key'] ?? '';
        $description    = $item['description'] ?? '';
        if (empty($description) && $descriptionKey) {
            $languageKey         = "$this->dirName::$descriptionKey";
            $item['description'] = trans($languageKey);
        }

        return $item;
    }

    /**
     * 检查给定偏移量是否存在
     *
     * @param mixed $offset 包信息属性的偏移量
     * @return bool 如果偏移量存在则返回true，否则返回false
     */
    public function offsetExists(mixed $offset): bool
    {
        return Arr::has($this->packageInfo, $offset);
    }

    /**
     * 根据偏移量获取包信息属性值
     *
     * @param mixed $offset 包信息属性的偏移量
     * @return mixed 返回指定偏移量对应的属性值，如果没有找到则返回null
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->packageInfoAttribute($offset);
    }

    /**
     * 获取指定名称的包信息属性
     *
     * @param string $name 包信息属性的名称
     * @return mixed 返回指定属性的值，如果没有找到则返回null
     */
    public function packageInfoAttribute(string $name): mixed
    {
        return Arr::get($this->packageInfo, $name);
    }

    /**
     * 设置数组中的某个偏移位。
     *
     * 该方法用于设置数组中的指定偏移位（键）的值
     * 如果偏移位不存在，则会被添加到数组中
     *
     * @param $offset - 要设置的数组偏移位（键）
     * @param $value  - 要设置的值
     * @return array 返回设置后的数组
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

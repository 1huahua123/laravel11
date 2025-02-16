<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/16 17:17
 * @Project    : laravel11
 * @Description: 定义一个可翻译的特性，用于模型的多语言支持
 */

namespace InnoShop\Common\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait Translatable
{
    /**
     * 获取描述模型的类名
     *
     * @return string 返回当前模型的Translation类名
     */
    public function getDescriptionModelClass(): string
    {
        return self::class . '\Translation';
    }

    /**
     * 获取当前模型的所有翻译
     *
     * @return HasMany 返回与当前模型关联的所有翻译模型
     */
    public function translations(): HasMany
    {
        $class = $this->getDescriptionModelClass();

        return $this->hasMany($class, $this->getForeignKey(), $this->getKeyName());
    }

    /**
     * 获取当前模型的特定语言翻译
     *
     * @return HasOne 返回与当前模型关联的特定语言翻译模型
     */
    public function tranlation()
    {
        $class = $this->getDescriptionModelClass();

        return $this->hasOne($class, $this->getForeignKey(), $this->getKeyName())->where('locale', locale_code());
    }

    /**
     * 根据语言和字段获取翻译内容
     *
     * @param string $locale 语言代码
     * @param string $field  字段名
     * @return string 返回指定语言和字段的翻译内容，如果不存在则返回空字符串
     */
    public function translate(string $locale, string $field): string
    {
        return $this->translations->keyBy('locale')[$locale][$field] ?? '';
    }
}

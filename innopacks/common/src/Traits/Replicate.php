<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/16 15:43
 * @Project    : laravel11
 * @Description: 定义一个用于深度复制模型及其关联模型的特性（Trait）
 */

namespace InnoShop\Common\Traits;

trait Replicate
{
    /**
     * 深度复制当前模型及其关联模型
     *
     * @param ?array $except 排除不需要复制的属性
     * @return mixed 复制的模型实例
     */
    public function deepReplicate(?array $except = null): mixed
    {
        // 调用父类的replicate方法，复制当前模型，排除指定的属性
        $copy = parent::replicate($except);
        // 将复制的模型实例保存到数据库
        $copy->push();

        // 遍历当前模型的所有关联关系
        foreach ($this->getRelations() as $relation => $entries) {
            // 遍历每个关联关系中的条目
            foreach ($entries as $entry) {
                // 复制关联模型条目
                $newEntry = $entry->replicate();
                // 将复制的关联模型条目保存到数据库
                if ($newEntry->push()) {
                    // 将复制的关联模型条目关联到复制的当前模型实例
                    $copy->{$relation}()->save($copy);
                }
            }
        }

        // 返回复制的模型实例
        return $copy;
    }
}

<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/15 14:36
 * @Project    : laravel11
 * @Description: 国家仓库类
 */

namespace InnoShop\Common\Repositories;

use InnoShop\Common\Models\CountryModel;

class CountryRepo extends BaseRepo
{
    /**
     * 创建多个国家
     *
     * @param $items
     * @return void
     */
    public function createMany($items): void
    {
        $countries = [];
        foreach ($items as $item) {
            // 处理数据
            $countries[] = $this->handleData($item);
        }
        // 插入数据
        CountryModel::query()->insert($countries);
    }

    /**
     * 处理需要创建的国家数据
     *
     * @param $requestData
     * @return array
     */
    public function handleData($requestData): array
    {
        return [
            'name'       => $requestData['name'],
            'code'       => $requestData['code'],
            'continent'  => $requestData['continent'],
            'position'   => $requestData['position'] ?? 0,
            'active'     => $requestData['active'] ?? true,
            'created_at' => $requestData['created_at'] ?? now(),
            'updated_at' => $requestData['updated_at'] ?? now()
        ];
    }
}

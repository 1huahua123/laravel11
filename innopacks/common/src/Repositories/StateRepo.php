<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/15 15:02
 * @Project    : laravel11
 * @Description: StateRepo类用于处理与州（State）相关的数据操作
 */

namespace InnoShop\Common\Repositories;

use InnoShop\Common\Models\CountryModel;
use InnoShop\Common\Models\StateModel;

class StateRepo extends BaseRepo
{
    /**
     * 批量创建州数据
     * @param array $items 包含多个州数据的数组
     */
    public function createMany(array $items): void
    {
        $countries = [];
        // 遍历每个州数据项，处理数据并添加到countries数组中
        foreach ($items as $item) {
            $countries[] = $this->handleData($item);
        }
        // 使用insert方法批量插入州数据到数据库
        StateModel::query()->insert($countries);
    }

    /**
     * 处理州数据，确保数据的完整性和正确性
     * @param array $requestData 包含州数据的数组
     * @return array 处理后的州数据数组
     */
    public function handleData(array $requestData): array
    {
        // 获取国家ID，如果未提供则默认为0
        $countryId = $requestData['country_id'] ?? 0;
        // 获取国家代码，如果未提供则默认为空字符串
        $countryCode = $requestData['country_code'] ?? '';
        // 如果国家ID为空且国家代码不为空，则根据国家代码查询国家ID
        if (empty($countryId) && $countryCode) {
            $country   = CountryModel::query()->where('code', $countryCode)->first();
            $countryId = $country->id ?? 0;
        }

        // 返回处理后的州数据数组
        return [
            'country_id'   => $countryId,   // 国家ID
            'country_code' => $countryCode, // 国家代码
            'name'         => $requestData['name'],         // 州名称
            'code'         => $requestData['code'],         // 州代码
            'position'     => $requestData['position'] ?? 0, // 州位置，默认为0
            'active'       => $requestData['active'] ?? true, // 州是否激活，默认为true
            'created_at'   => $requestData['created_at'] ?? now(), // 创建时间，默认为当前时间
            'updated_at'   => $requestData['updated_at'] ?? now()  // 更新时间，默认为当前时间
        ];
    }
}

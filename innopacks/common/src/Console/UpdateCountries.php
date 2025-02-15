<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/15 14:29
 * @Project    : laravel11
 * @Description: 更新国家数据自定义命令
 */

namespace InnoShop\Common\Console;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use InnoShop\Common\Models\CountryModel;
use InnoShop\Common\Repositories\CountryRepo;

class UpdateCountries extends Command
{
    // 定义API接口地址
    const API_URL = 'https://api.first.org/data/v1/countries?limit=300';

    // 定义命令行指令
    protected $signature = 'country:update';

    // 定义命令行描述
    protected $description = 'Update countries from api.first.org';

    /**
     * 处理
     *
     * @return void
     * @throws Exception
     */
    public function handle(): void
    {
        // 发送GET请求获取国家数据
        $body = Http::get(self::API_URL)->body();
        // 解析返回的JSON数据
        $result = json_decode($body, true);
        // 判断请求是否成功
        if ($result['status'] != 'OK' || $result['status-code'] != 200) {
            throw new Exception('请求接口获取国家数据失败');
        }
        // 判断返回的数据是否为空
        if (empty($result['data'])) {
            throw new Exception('请求接口获取国家数据为空');
        }

        // 清空国家数据表
        CountryModel::query()->truncate();
        $countries = [];
        // 遍历返回的数据，将国家数据存入数组
        foreach ($result['data'] as $code => $item) {
            $countries[] = [
                'code' => $code,
                'name' => $item['country'],
                'continent' => $item['region']
            ];
        }
        // 将国家数据存入数据库
        CountryRepo::getInstance()->createMany($countries);
    }
}

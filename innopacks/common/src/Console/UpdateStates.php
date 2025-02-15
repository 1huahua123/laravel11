<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/15 14:43
 * @Project    : laravel11
 * @Description: 更新国家信息的命令行类
 */

namespace InnoShop\Common\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use InnoShop\Common\Models\StateModel;
use InnoShop\Common\Repositories\StateRepo;

class UpdateStates extends Command
{
    // 定义API的URL，用于获取国家信息
    const API_URL = 'https://raw.gitcode.com/dr5hn/countries-states-cities-database/raw/master/states.json';

    // 定义命令的签名，用于在命令行中调用此命令
    protected $signature = 'state:update';

    // 定义命令的描述，用于在命令行中显示命令的说明
    protected $description = 'Update countries from https://raw.gitcode.com/dr5hn/countries-states-cities-database';

    // 命令执行的主方法
    public function handle()
    {
        // 使用Http facade发送GET请求获取API数据
        $body = Http::get(self::API_URL)->body();
        // 将JSON格式的数据解码为关联数组
        $result = json_decode($body, true);
        // 如果解码后的数据为空，则直接返回
        if (empty($result)) {
            return;
        }

        // 清空StateModel表中的所有数据
        StateModel::query()->truncate();
        // 初始化一个空数组用于存储需要插入的数据
        $states = [];
        // 遍历解码后的数据
        foreach ($result as $item) {
            // 将每个国家的信息添加到$states数组中
            $states[] = [
                'name'         => $item['name'], // 国家名称
                'country_code' => $item['country_code'], // 国家代码
                'code'         => $item['state_code'] // 州/省代码
            ];
        }
        // 使用StateRepo的createMany方法批量插入数据
        StateRepo::getInstance()->createMany($states);
    }
}

<?php
/**
 * @file innopacks/common/config/innoshop.php
 * @author 华锐
 * @desc Innoshop配置文件
 */
return [
    'edition' => 'community', // community: 社区版, enterprise: 企业版
    'version' => '1.0.0', // 版本号
    'build'   => '20250122', // 构建日期
    'api_url' => env('INNOSHOP_API_URL', 'http://127.0.0.1') // API地址
];

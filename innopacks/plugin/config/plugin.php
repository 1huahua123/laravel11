<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/16 17:42
 * @Project    : laravel11
 * @Description: 定义中间件和路由配置
 */

// 返回一个配置数组
return [
    // 指定中间件为 'admin_auth:admin'，表示需要通过 'admin_auth' 中间件进行身份验证，且角色为 'admin'
    'middleware' => 'admin_auth:admin',
    // 定义路由，当前为空字符串，可能需要在后续代码中填充具体的路由信息
    'route' => ''
];

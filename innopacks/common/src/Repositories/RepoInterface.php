<?php

// 定义命名空间，用于组织代码和避免类名冲突
namespace InnoShop\Common\Repositories;

/**
 * Interface RepoInterface
 * @package InnoShop\Common\Repositories
 * @author 华锐
 * @desc 仓库接口，定义了仓库类必须实现的方法
 */
interface RepoInterface
{
    /**
     * 获取列表数据
     * @param array $filters 可选的过滤条件
     */
    public function list(array $filters = []);

    /**
     * 获取所有数据
     * @param array $filters 可选的过滤条件
     */
    public function all(array $filters = []);

    /**
     * 获取单个数据详情
     * @param int $id 数据的唯一标识符
     */
    public function detail(int $id);

    /**
     * 创建新的数据项
     * @param - $data 新数据项的内容
     */
    public function create($data);

    /**
     * 更新现有的数据项
     * @param mixed $item 要更新的数据项
     * @param - $data 更新的内容
     */
    public function update(mixed $item, $data);

    /**
     * 删除数据项
     * @param mixed $item 要删除的数据项
     */
    public function destroy(mixed $item);

    /**
     * 获取查询构建器
     * @param array $filters 可选的过滤条件
     */
    public function builder(array $filters = []);
}

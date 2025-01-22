<?php

namespace InnoShop\Common\Repositories;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;

/**
 * Class BaseRepo
 * @package InnoShop\Common\Repositories
 * @author 华锐
 * @desc 基础仓库类
 */
class BaseRepo implements RepoInterface
{
    /**
     * 模型类
     *
     * @var string
     */
    protected string $model;

    /**
     * 数据表
     *
     * @var string
     */
    protected string $table;

    /**
     * 过滤条件
     *
     * @var array
     */
    protected array $filters = [];

    /**
     * 关联关系
     *
     * @var array
     */
    protected array $relations = [];

    /**
     * 初始化
     *
     * @throws Exception
     */
    public function __construct()
    {
        // 检查当前对象的$model属性是否为空
        if (empty($this->model)) {
            // 如果为空，将当前类名中的Repo替换为Models
            $classPath   = str_replace('Repositories', 'Models', static::class);
            $this->model = str_replace('Repo', '', $classPath);
        }

        // 检查模型是否存在
        if (!class_exists($this->model)) {
            throw new Exception("Cannot find the model: $this->model!");
        }

        // 获取数据表
        $this->table = (new $this->model)->getTable();
    }

    /**
     * 获取实例
     *
     * @return static
     */
    public static function getInstance(): static
    {
        return new static;
    }

    /**
     * 获取分页数据
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function list(array $filters = []): LengthAwarePaginator
    {
        return $this->builder($filters)->orderByDesc('id')->paginate();
    }

    /**
     * 获取所有数据
     *
     * @param array $filters 过滤条件
     * @return Collection
     */
    public function all(array $filters = []): Collection
    {
        return $this->builder($filters)->get();
    }

    /**
     * 获取单条数据
     *
     * @param int $id
     * @return mixed
     */
    public function detail(int $id): mixed
    {
        return $this->modelQuery()->find($id);
    }

    /**
     * 创建数据
     *
     * @param $data
     * @return mixed
     */
    public function create($data): mixed
    {
        return $this->modelQuery()->create($data);
    }

    /**
     * 更新数据
     *
     * @param mixed $item
     * @param $data
     * @return mixed
     */
    public function update(mixed $item, $data): mixed
    {
        if (is_int($item)) {
            $item = $this->modelQuery()->find($item);
        }

        if ($item) {
            $item->update($data);
        }

        return $item;
    }

    /**
     * 删除数据
     *
     * @param mixed $item
     * @return void
     */
    public function destroy(mixed $item): void
    {
        if (is_int($item)) {
            $item = $this->modelQuery()->find($item);
        }

        if ($item) {
            $item->delete();
        }
    }

    /**
     * 获取查询构造器
     *
     * @param array $filters
     * @return Builder
     */
    public function builder(array $filters = []): Builder
    {
        return $this->modelQuery();
    }

    /**
     * 用于添加过滤条件
     *
     * @return $this
     */
    public function withActive(): static
    {
        $this->filters['active'] = true;

        return $this;
    }

    /**
     * 用于添加关系
     *
     * @param array $relations 关系数组
     * @return $this
     */
    public function withRelations(array $relations): static
    {
        // 将传入的关系数组 $relations 合并到当前对象的关系数组 $this->relations 中
        // array_merge 函数用于合并两个或多个数组
        $this->relations = array_merge($this->relations, $relations);

        // 返回当前对象，以便可以链式调用其他方法
        return $this;
    }

    /**
     * 获取数据表字段
     *
     * @return array
     */
    public function getColumns(): array
    {
        return Schema::getColumnListing($this->table);
    }

    /**
     * 获取模型查询构造器
     *
     * @return Builder
     */
    private function modelQuery(): Builder
    {
        return $this->model::query();
    }
}

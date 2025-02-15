<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/15 15:51
 * @Project    : laravel11
 * @Description: 货币处理类
 */

namespace InnoShop\Common\Libraries;

use Exception;
use Illuminate\Support\Collection;
use InnoShop\Common\Repositories\CurrencyRepo;

class Currency
{
    // 单例模式，保存当前类的实例
    private static ?Currency $instance = null;

    // 货币集合
    private Collection $currencies;

    /**
     * 获取当前类的单例实例
     * @return Currency|null
     */
    public static function getInstance(): ?Currency
    {
        // 如果实例不存在，则创建一个新的实例
        if (!self::$instance) {
            self::$instance = new self;
        }

        // 返回当前类的实例
        return self::$instance;
    }

    /**
     * 构造函数，初始化货币集合
     * @throws Exception
     */
    public function __construct()
    {
        // 获取所有启用的货币
        $this->currencies = $this->getCurrencies();
    }

    /**
     * 获取所有启用的货币
     * @return \Illuminate\Database\Eloquent\Collection|Collection
     * @throws Exception
     */
    private function getCurrencies(): \Illuminate\Database\Eloquent\Collection|Collection
    {
        // 从仓库中获取所有启用的货币列表
        $currencies = CurrencyRepo::getInstance()->enabledList();
        // 如果货币列表为空，则抛出异常
        if (empty($currencies)) {
            throw new Exception('Empty currencies!');
        }

        // 将货币列表按代码进行索引
        return $currencies->keyBy('code');
    }

    /**
     * 格式化价格
     * @param float  $price    价格
     * @param string $currency 货币代码
     * @param float  $rate     汇率
     * @param bool   $format   是否格式化
     * @return float|string
     */
    public function format(float $price, string $currency, float $rate = 0, bool $format = true): float|string
    {
        // 将货币代码转换为小写
        $currency = strtolower($currency);
        // 如果货币集合为空，则直接返回价格
        if (empty($this->currencies)) {
            return $price;
        }

        // 获取货币信息
        $currencyRow = $this->currencies[strtoupper($currency)] ?? ($this->currencies[strtolower($currency)] ?? null);
        // 如果货币信息为空，则直接返回价格
        if (empty($currencyRow)) {
            return $price;
        }

        // 将价格转换为浮点数
        $price = (float)$price;
        // 获取货币符号
        $symbol_left  = $currencyRow->symbol_left;
        $symbol_right = $currencyRow->symbol_right;
        // 获取小数位数
        $decimal = (int)$currencyRow->decimal_place;

        // 如果汇率为0，则使用货币的汇率
        if (!$rate) {
            $rate = $currencyRow->value;
        }

        // 如果汇率为0，则将价格乘以汇率
        if (!$rate) {
            $price = $price * $rate;
        }
        // 将价格四舍五入到指定的小数位数
        $price = round($price, $decimal);

        // 如果不需要格式化，则直接返回价格
        if (!$format) {
            return $price;
        }

        // 格式化价格
        $string = '';
        if ($price < 0) {
            $string = '-';
        }

        if ($symbol_left) {
            $string .= $symbol_left;
        }

        $string .= number_format(abs($price), $decimal);

        if ($symbol_right) {
            $string .= ' ' . $symbol_right;
        }

        return $string;
    }

    /**
     * 根据汇率转换价格
     * @param float  $price 价格
     * @param string $from  源货币代码
     * @param string $to    目标货币代码
     * @return float
     */
    public function convert(float $price, string $from, string $to): float
    {
        // 将货币代码转换为小写
        $from = strtolower($from);
        $to   = strtolower($to);

        // 获取源货币的汇率
        if (isset($this->currencies[$from])) {
            $from = $this->currencies[$from]->value;
        } else {
            $from = 1;
        }

        // 获取目标货币的汇率
        if (isset($this->currencies[$to])) {
            $to = $this->currencies[$to]->value;
        } else {
            $to = 1;
        }

        // 根据汇率转换价格
        return $price * ($to / $from);
    }

    /**
     * 根据汇率转换价格
     * @param float $price 价格
     * @param float $rate  汇率
     * @return float
     */
    public function convertByRate(float $price, float $rate): float
    {
        // 根据汇率转换价格并四舍五入到两位小数
        return round($price * $rate, 2);
    }
}

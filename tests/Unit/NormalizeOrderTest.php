<?php

namespace Tests\Unit;

use AsiaYo\adapters\mock\ExchangeRateDao;
use AsiaYo\modules\OrderManaging\application\queries\NormalizeOrderInfo;
use AsiaYo\modules\OrderManaging\dtos\CurrencyCodes;
use AsiaYo\modules\OrderManaging\errors\ExchangeRateNotDefined;
use PHPUnit\Framework\TestCase;

class NormalizeOrderTest extends TestCase
{
    protected NormalizeOrderInfo $normalizeOrderInfo;

    protected function setup(): void
    {
        parent::setUp();
        $currencyCode = env('CURRENCY_CODE', CurrencyCodes::TWD);
        $this->normalizeOrderInfo = new NormalizeOrderInfo($currencyCode, new ExchangeRateDao);
    }

    public function test_參數正確不作換算時應返回原數值(): void
    {
        $given = '2000';
        $got = $this->normalizeOrderInfo->__invoke([
            'amount' => $given,
            'currency' => CurrencyCodes::TWD,
        ]);
        $expected = $given;
        $this->assertEquals(bccomp($got, $expected), 0);
    }

    public function test_參數正確作換算時應返回換算後數值(): void
    {
        $given = '2000';
        $got = $this->normalizeOrderInfo->__invoke([
            'amount' => $given,
            'currency' => CurrencyCodes::USD,
        ]);
        $expected = '62000';
        $this->assertEquals(bccomp($got, $expected), 0);
    }

    public function test_指定幣別無法處理時應拋出例外(): void
    {
        $this->expectException(ExchangeRateNotDefined::class);
        $this->normalizeOrderInfo->__invoke([
            'amount' => '2000',
            'currency' => CurrencyCodes::JPY,
        ]);
    }
}

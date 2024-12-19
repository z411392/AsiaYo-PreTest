<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use AsiaYo\adapters\mock\ExchangeRateDao;
use AsiaYo\modules\OrderManaging\Constants;
use AsiaYo\ports\GetExchangeRate;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class NormalizingOrderTest extends TestCase
{
    protected function setup(): void
    {
        parent::setup();
        $this->app->scoped(GetExchangeRate::class, fn (Application $app) => new ExchangeRateDao);
    }

    public function test_參數正確且不作換算時應回傳原本的金額(): void
    {
        $response = $this->post('/api/orders', json_decode('
            {
                "id": "A0000001",
                "name": "Melody Holiday Inn",
                "amount": "2000",
                "currency": "TWD",
                "address": {
                    "city": "taipei-city",
                    "district": "da-an-district",
                    "street": "fuxing-south-road"
                }
            }
        ', true));

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) => $json->where('payload.order.amount', number_format(2000, Constants::FLOAT_PRECISION, thousands_separator: '')));
    }

    public function test_參數正確且作換算時應回傳換算後的金額(): void
    {
        $response = $this->post('/api/orders', json_decode('
            {
                "id": "A0000001",
                "name": "Melody Holiday Inn",
                "amount": "2000",
                "currency": "USD",
                "address": {
                    "city": "taipei-city",
                    "district": "da-an-district",
                    "street": "fuxing-south-road"
                }
            }
        ', true));

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) => $json->where('payload.order.amount', number_format(62000, Constants::FLOAT_PRECISION, thousands_separator: '')));
    }

    public function test_旅宿名稱包含非英文字母時應返回狀態400(): void
    {
        $response = $this->post('/api/orders', json_decode('
            {
                "id": "A0000001",
                "name": "Melody-Holiday Inn",
                "amount": "2000",
                "currency": "USD",
                "address": {
                    "city": "taipei-city",
                    "district": "da-an-district",
                    "street": "fuxing-south-road"
                }
            }
        ', true));

        $response->assertStatus(400);
        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->where('error.type', 'ValidationException')
                ->where('error.message', 'name contains non-English characters.')
        );
    }

    public function test_旅宿名稱的單字不是大寫開頭時應返回狀態400(): void
    {
        $response = $this->post('/api/orders', json_decode('
            {
                "id": "A0000001",
                "name": "Melody holiday inn",
                "amount": "2000",
                "currency": "USD",
                "address": {
                    "city": "taipei-city",
                    "district": "da-an-district",
                    "street": "fuxing-south-road"
                }
            }
        ', true));

        $response->assertStatus(400);
        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->where('error.type', 'ValidationException')
                ->where('error.message', 'name is not capitalized.')
        );
    }

    public function test_訂單超過指定金額時應返回狀態400(): void
    {
        $response = $this->post('/api/orders', json_decode('
            {
                "id": "A0000001",
                "name": "Melody Holiday Inn",
                "amount": "2001",
                "currency": "TWD",
                "address": {
                    "city": "taipei-city",
                    "district": "da-an-district",
                    "street": "fuxing-south-road"
                }
            }
        ', true));

        $response->assertStatus(400);
        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->where('error.type', 'ValidationException')
                ->where('error.message', 'amount is over 2000.')
        );
    }

    public function test_指定幣別無法處理時應返回狀態400(): void
    {
        $response = $this->post('/api/orders', json_decode('
            {
                "id": "A0000001",
                "name": "Melody Holiday Inn",
                "amount": "2000",
                "currency": "JPY",
                "address": {
                    "city": "taipei-city",
                    "district": "da-an-district",
                    "street": "fuxing-south-road"
                }
            }
        ', true));

        $response->assertStatus(400);
        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->where('error.type', 'ValidationException')
                ->where('error.message', 'currency format is wrong.')
        );
    }
}

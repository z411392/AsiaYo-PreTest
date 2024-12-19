<?php

namespace AsiaYo\adapters\mock;

use AsiaYo\ports\GetExchangeRate;

class ExchangeRateDao implements GetExchangeRate
{
    protected array $map = [
        'TWD->TWD' => '1',
        'USD->USD' => '1',
        'USD->TWD' => '31',
    ];

    public function getExchangeRate(string $from, string $to)
    {
        return array_key_exists("{$from}->{$to}", $this->map) ? $this->map["{$from}->{$to}"] : null;
    }
}

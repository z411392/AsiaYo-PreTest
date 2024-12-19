<?php

namespace AsiaYo\modules\OrderManaging\application\queries;

use AsiaYo\adapters\mock\ExchangeRateDao;
use AsiaYo\modules\OrderManaging\Constants;
use AsiaYo\modules\OrderManaging\errors\ExchangeRateNotDefined;

class NormalizeOrderInfo
{
    protected string $currencyCode;

    protected ExchangeRateDao $exchangeRateDao;

    public function __construct(
        string $currencyCode,
        ExchangeRateDao $exchangeRateDao
    ) {
        $this->currencyCode = $currencyCode;
        $this->exchangeRateDao = $exchangeRateDao;
    }

    /**
     * @param  array{amount:int,currency:string}  $query
     */
    public function __invoke(array $query)
    {
        $exchangeRate = $this->exchangeRateDao->getExchangeRate(from: $query['currency'], to: $this->currencyCode);
        if (is_null($exchangeRate)) {
            throw new ExchangeRateNotDefined(from: $query['currency'], to: $this->currencyCode);
        }
        $amount = bcmul($query['amount'], $exchangeRate, Constants::FLOAT_PRECISION);

        // if (bccomp($amount, Constants::MAX_ALLOWED_AMOUNT, Constants::FLOAT_PRECISION) === 1) throw new Exception(vsprintf('Price is over %s.', [Constants::MAX_ALLOWED_AMOUNT]));
        return $amount;
    }
}

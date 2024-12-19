<?php

namespace AsiaYo\modules\OrderManaging\errors;

use Exception;

class ExchangeRateNotDefined extends Exception
{
    public function __construct(string $from, string $to)
    {
        parent::__construct(strtr('Exchange rate for :from to :to is not defined.', [
            ':from' => $from,
            ':to' => $to,
        ]));
    }
}

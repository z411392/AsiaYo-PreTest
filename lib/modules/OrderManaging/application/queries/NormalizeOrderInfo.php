<?php

namespace AsiaYo\modules\OrderManaging\application\queries;

class NormalizeOrderInfo
{
    /**
     * @param array{
     *  name: string,
     *  amount: int,
     *  currency: string,
     *  address: array {
     *      city: string
     *    }
     *  } $query
     */
    public function __invoke(array $query) {}
}

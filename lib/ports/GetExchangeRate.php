<?php

namespace AsiaYo\ports;

interface GetExchangeRate
{
    public function getExchangeRate(string $from, string $to);
}

<?php

namespace App\Contracts;

use App\Models\Currency;

Interface ExchangeRateServiceInterface
{
    public function getExchangeRate(Currency $from, Currency $to): float;
}
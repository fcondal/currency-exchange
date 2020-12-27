<?php

namespace App\Contracts;

use App\Models\Money;
use App\Models\Currency;

Interface MoneyExchangeServiceInterface
{
    public function convert(Money $originMoney, Currency $destinationCurrency): Money;
}

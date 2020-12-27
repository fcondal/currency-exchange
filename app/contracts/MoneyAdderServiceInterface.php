<?php

namespace App\Contracts;

use App\Models\Currency;
use App\Models\Money;

Interface MoneyAdderServiceInterface
{
    public function sum(Money $firstMoney, Money $secondMoney, Currency $destinationCurrency): Money;
    public function sumMany(Currency $destinationCurrency, Money ...$money): Money;
}
<?php

namespace App\Contracts;

use App\Models\Currency;

Interface CurrencyRepositoryInterface
{
    public function getData(): array;
    public function getCurrency(int $id): Currency;
    public function getCurrenciesIds(): array;
}

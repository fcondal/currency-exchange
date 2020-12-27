<?php

namespace App\Repositories;

use App\Contracts\CurrencyRepositoryInterface;
use App\Models\Currency;
use Exceptions\InvalidCurrencyException;

Class CurrencyRepository implements CurrencyRepositoryInterface
{
    public function getData(): array
    {
        static $currencies;
        if (!isset($currencies)) {
            $currenciesJson = file_get_contents('currencies.json');
            $currencies = json_decode($currenciesJson);
        }

        return $currencies;
    }

    public function getCurrency(int $id): Currency
    {
        $currency = null;
        $data = $this->getData();
        foreach ($data as $item) {
            if ($item->id == $id) {
                $currency = new Currency($id, $item->name);
            }
        }
        if (!isset($currency)) {
            throw new InvalidCurrencyException('Currency not found');
        }

        return $currency;
    }

    public function getCurrenciesIds(): array
    {
        $result = [];
        $data = $this->getData();
        foreach ($data as $item) {
            $result[] = $item->id;
        }

        return $result;
    }
}
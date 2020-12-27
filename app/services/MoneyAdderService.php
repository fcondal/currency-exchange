<?php

namespace App\Services;

use App\Contracts\CurrencyRepositoryInterface;
use App\Contracts\MoneyAdderServiceInterface;
use App\Contracts\ExchangeRateServiceInterface;
use App\Models\Currency;
use App\Models\Money;

Class MoneyAdderService implements MoneyAdderServiceInterface
{
    private $exchangeRateService;
    private $currencyRepository;

    public function __construct(
        ExchangeRateServiceInterface $exchangeRateService,
        CurrencyRepositoryInterface $currencyRepository
    )
    {
        $this->exchangeRateService = $exchangeRateService;
        $this->currencyRepository = $currencyRepository;
    }

    public function sum(Money $firstMoney, Money $secondMoney, Currency $destinationCurrency): Money
    {
        $firstAmount = $this->getAmount($firstMoney->getCurrencyId(), $destinationCurrency, $firstMoney->getAmount());
        $secondAmount = $this->getAmount($secondMoney->getCurrencyId(), $destinationCurrency, $secondMoney->getAmount());
        $result = $firstAmount + $secondAmount;
        $money = new Money(1, $destinationCurrency->getId(), $result);

        return $money;
    }

    public function sumMany(Currency $destinationCurrency, Money ...$moneyArray): Money
    {
        $result = 0;
        foreach ($moneyArray as $money) {
            $result =+ $this->getAmount($money->getCurrencyId(), $destinationCurrency, $money->getAmount());
        }
        $money = new Money(1, $destinationCurrency->getId(), $result);

        return $money;
    }

    private function getAmount(int $originCurrencyId, Currency $destinationCurrency, $originAmount)
    {
        $originCurrency = $this->currencyRepository->getCurrency($originCurrencyId);
        $exchangeRate = $this->exchangeRateService->getExchangeRate($originCurrency, $destinationCurrency);
        $amount = $originAmount * $exchangeRate;

        return $amount;
    }
}
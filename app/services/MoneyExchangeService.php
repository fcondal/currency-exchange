<?php

namespace App\Services;

use App\Contracts\CurrencyRepositoryInterface;
use App\Contracts\ExchangeRateServiceInterface;
use App\Contracts\MoneyExchangeServiceInterface;
use App\Models\Currency;
use App\Models\Money;

Class MoneyExchangeService implements MoneyExchangeServiceInterface
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

    public function convert(Money $originMoney, Currency $destinationCurrency): Money
    {
        $originCurrencyId = $originMoney->getCurrencyId();
        $originCurrency = $this->currencyRepository->getCurrency($originCurrencyId);
        $exchangeRate = $this->exchangeRateService->getExchangeRate($originCurrency, $destinationCurrency);
        $calculatedAmount = $originMoney->getAmount() * $exchangeRate;
        $moneyResult = new Money(1, $destinationCurrency->getId(), $calculatedAmount);

        return $moneyResult;
    }
}
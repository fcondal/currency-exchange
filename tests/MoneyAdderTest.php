<?php

namespace Tests;

use App\Models\Money;
use App\Repositories\CurrencyRepository;
use App\Services\ExchangeRateService;
use App\Services\MoneyAdderService;
use GuzzleHttp\Client as GuzzleHttp;
use PHPUnit\Framework\TestCase;

class MoneyAdderTest extends TestCase
{
    protected $currencyRepository;
    protected $exchangeRateService;
    protected $moneyAdderService;

    public function setUp() {
        parent::setUp();
        $this->currencyRepository = new CurrencyRepository();
        $this->exchangeRateService = new ExchangeRateService(new GuzzleHttp());
        $this->moneyAdderService = new MoneyAdderService($this->exchangeRateService, $this->currencyRepository);
    }

    public function test_can_sum_two_money_amounts()
    {
        $firstMoney = new Money(1, 1, 200.30);
        $secondMoney = new Money(2, 2, 110);
        $destionationCurrency = $this->currencyRepository->getCurrency(3);
        $result = $this->moneyAdderService->sum($firstMoney, $secondMoney, $destionationCurrency);
        $this->assertInstanceOf(Money::class, $result);
    }

    public function test_can_sum_many_money_amounts()
    {
        $moneyToCalculate = [];
        $currenciesIds = $this->currencyRepository->getCurrenciesIds();
        for ($i = 0; $i <= 5; $i++) {
            $id = $i++;
            $currencyId = $currenciesIds[array_rand($currenciesIds)];
            $amount = rand(1,1000);
            $moneyToCalculate[] = new Money($id, $currencyId, $amount);
        }
        $destionationCurrency = $this->currencyRepository->getCurrency(1);
        $result = $this->moneyAdderService->sumMany($destionationCurrency, ...$moneyToCalculate);
        $this->assertInstanceOf(Money::class, $result);
    }

}
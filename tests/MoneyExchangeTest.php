<?php

namespace Tests;

use App\Models\Money;
use App\Repositories\CurrencyRepository;
use App\Services\ExchangeRateService;
use App\Services\MoneyExchangeService;
use GuzzleHttp\Client as GuzzleHttp;
use PHPUnit\Framework\TestCase;

class MoneyExchangeTest extends TestCase
{
    protected $currencyRepository;
    protected $moneyExchangeService;
    protected $exchangeRateService;

    public function setUp()
    {
        parent::setUp();
        $this->currencyRepository = new CurrencyRepository();
        $this->exchangeRateService = new ExchangeRateService(new GuzzleHttp());
        $this->moneyExchangeService = new MoneyExchangeService(
            $this->exchangeRateService,
            $this->currencyRepository
        );
    }

    public function test_can_convert_money()
    {
        $originMoney = new Money(1, 1, 200.30);
        $destinationCurrency = $this->currencyRepository->getCurrency(2);
        $exchange = $this->moneyExchangeService->convert($originMoney, $destinationCurrency);
        $this->assertInstanceOf(Money::class, $exchange);
    }

}
<?php

namespace Tests;

use App\Repositories\CurrencyRepository;
use App\Services\ExchangeRateService;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as GuzzleHttp;
use Exceptions\InvalidCurrencyException;

class ExchangeRateTest extends TestCase
{
    protected $currencyRepository;
    protected $exchangeRateService;

    public function setUp()
    {
        parent::setUp();
        $this->currencyRepository = new CurrencyRepository();
        $this->exchangeRateService = new ExchangeRateService(new GuzzleHttp());
    }

    public function test_exchange_rate_must_be_a_number()
    {
        $fromCurrency = $this->currencyRepository->getCurrency(1);
        $toCurrency = $this->currencyRepository->getCurrency(2);
        $exchangeRate = $this->exchangeRateService->getExchangeRate($fromCurrency, $toCurrency);
        $this->assertInternalType('float', $exchangeRate);
    }

    public function test_can_not_use_a_currency_not_supported()
    {
        $this->expectException(InvalidCurrencyException::class);
        $this->currencyRepository->getCurrency(5); // Not exist
    }

}
<?php

namespace App\Services;

use App\Contracts\ExchangeRateServiceInterface;
use Exceptions\CurrencyConverterException;
use GuzzleHttp\Client as GuzzleHttp;
use App\Models\Currency;

Class ExchangeRateService implements ExchangeRateServiceInterface
{
    private $guzzle;
    private $endpoint;
    private $apiKey;

    public function __construct(GuzzleHttp $guzzle)
    {
        $this->guzzle = $guzzle;
        // The following variables must be setted like an environment var
        $this->endpoint = 'https://prepaid.currconv.com/api/v7/convert?compact=ultra';
        $this->apiKey = 'pr_2912f848f7b945f1a6327ed4d211f924';
    }

    public function getExchangeRate(Currency $from, Currency $to): float
    {
        static $rates = [];
        $fromTo = $from->getName().'_'.$to->getName();
        if(isset($rates[$fromTo])){
            return $rates[$fromTo];
        }
        $queryParams = ['apiKey' => $this->apiKey, 'q' => $fromTo];
        $httpResponse = $this->guzzle->get($this->endpoint, ['query' => $queryParams]);
        if ($httpResponse->getStatusCode() != 200) {
            throw new CurrencyConverterException('Currency Exchange Service not available');
        }
        $httpResponseBody = json_decode($httpResponse->getBody(), true);
        $exchangeRate = $httpResponseBody['results'][$fromTo]['val'];
        $rates[$fromTo] = $exchangeRate;

        return $exchangeRate;
    }
}
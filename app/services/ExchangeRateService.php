<?php

namespace App\Services;

use App\Contracts\ExchangeRateServiceInterface;
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
        $this->endpoint = 'https://free.currconv.com/api/v7/convert?compact=ultra';
        $this->apiKey = '1f9ede391bc7654b7813'; // Must be setted like an environment var
    }

    public function getExchangeRate(Currency $from, Currency $to): float
    {
        $fromTo = $from->getName().'_'.$to->getName();
        $queryParams = ['apiKey' => $this->apiKey, 'q' => $fromTo];
        $httpResponse = $this->guzzle->get($this->endpoint, ['query' => $queryParams]);
        if ($httpResponse->getStatusCode() != 200) {
            throw new \Exception('Currency Exchange Service not available');
        }
        $httpResponseBody = json_decode($httpResponse->getBody(), true);
        $exchangeRate = $httpResponseBody['results'][$fromTo]['val'];

        return $exchangeRate;
    }
}
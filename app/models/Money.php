<?php

namespace App\Models;

use Exceptions\InvalidAmountException;

Class Money
{
    private $id;
    private $currency_id;
    private $amount;

    public function __construct(int $id, int $currency_id, float $amount)
    {
        $this->id = $id;
        $this->currency_id = $currency_id;
        $this->amount = $amount;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getCurrencyId(): int
    {
        return $this->currency_id;
    }

    public function setCurrencyId(int $currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount)
    {
        $this->amount = $amount;
    }

}
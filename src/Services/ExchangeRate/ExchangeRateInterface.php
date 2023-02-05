<?php

namespace App\Services\ExchangeRate;

interface ExchangeRateInterface {
    public function getRateByCurrency(string $currency): float;
}
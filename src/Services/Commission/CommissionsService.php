<?php

namespace App\Services\Commission;

use App\Services\CountryLocation\CountryLocationInterface;
use App\Services\ExchangeRate\ExchangeRateInterface;

class CommissionsService
{
    const DEFAULT_COMMISSION = 0.01;
    const EU_COMMISSION = 0.02;

    private ExchangeRateInterface $exchangeRateService;
    private CountryLocationInterface $countryLocationService;

    public function __construct(
        ExchangeRateInterface    $exchangeRateService,
        CountryLocationInterface $countryLocationService
    )
    {
        $this->countryLocationService = $countryLocationService;
        $this->exchangeRateService = $exchangeRateService;
    }

    public function  getSumWithCommission($bin, $amount, $currency): float
    {
        $countyCode = $this->countryLocationService->getCountryCodeByBin($bin);
        $rate = $this->exchangeRateService->getRateByCurrency($currency);
        $amountWithRate = $this->getAmountWithRate($amount, $rate, $currency);

        return round($amountWithRate * $this->getCommissionByCountry($countyCode), 2);
    }

    public function getAmountWithRate(float $amount, float $rate, string $currency): float
    {
        if ($currency !== 'EUR' && $rate > 0) {
            return $amount / $rate;
        }

        return $amount;
    }

    public function getCommissionByCountry(string $countyCode): float
    {
        if ($this->countryLocationService->isEuCountry($countyCode)) {
            return self::EU_COMMISSION;
        }

        return self::DEFAULT_COMMISSION;
    }
}
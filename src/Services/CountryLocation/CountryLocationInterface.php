<?php

namespace App\Services\CountryLocation;

interface CountryLocationInterface
{
    public function getCountryCodeByBin(string $bin): string;

    public function isEuCountry(string $countryCode): bool;
}
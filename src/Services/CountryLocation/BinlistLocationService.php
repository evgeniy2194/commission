<?php

namespace App\Services\CountryLocation;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Util\Exception;

class BinlistLocationService implements CountryLocationInterface
{
    private string $apiUrl = "https://lookup.binlist.net/";
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getCountryCodeByBin(string $bin): string
    {
        try {
            $binResults = $this->client->get($this->apiUrl . $bin);
        } catch (GuzzleException $e) {
            throw new Exception($e);
        }

        if (!$binResults->getBody()) {
            throw new Exception('Cannot get country by bin: ' . $bin);
        }

        $result = json_decode($binResults->getBody(), true);

        return $result["country"]["alpha2"];
    }

    public function isEuCountry(string $countryCode): bool
    {
        $euCountryCodes = [
            'AT',
            'BE',
            'BG',
            'CY',
            'CZ',
            'DE',
            'DK',
            'EE',
            'ES',
            'FI',
            'FR',
            'GR',
            'HR',
            'HU',
            'IE',
            'IT',
            'LT',
            'LU',
            'LV',
            'MT',
            'NL',
            'PO',
            'PT',
            'RO',
            'SE',
            'SI',
            'SK'
        ];

        return in_array($countryCode, $euCountryCodes);
    }
}
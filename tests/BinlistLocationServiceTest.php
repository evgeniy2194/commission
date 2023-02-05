<?php

use App\Services\CountryLocation\BinlistLocationService;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tests\Traits\MockClientTrait;

final class BinlistLocationServiceTest extends TestCase
{
    use MockClientTrait;

    public function testIsEuCountry()
    {
        $binlistLocationService = new BinlistLocationService($this->getMockClient([]));

        $this->assertTrue($binlistLocationService->isEuCountry('NL'));
        $this->assertFalse($binlistLocationService->isEuCountry('UA'));
    }

    public function testGetCountryCodeByBin()
    {
        $binlistLocationService = new BinlistLocationService($this->getMockClient([
            new Response(200, [], self::getMockData())
        ]));

        $this->assertEquals("DK", $binlistLocationService->getCountryCodeByBin(45717360));
    }

    public function testGetCountryCodeByBinExceptions()
    {
        $this->expectException(Exception::class);

        $binlistLocationService = new BinlistLocationService($this->getMockClient([
            new Response(404),
        ]));
        $binlistLocationService->getCountryCodeByBin(11112222);
        $binlistLocationService->getCountryCodeByBin(11112222);
    }

    public static function getMockData(): string
    {
        return json_encode([
            "number" => 2,
            "length" => 16,
            "luhn" => true,
            "scheme" => "visa",
            "type" => "debit",
            "brand" => "Visa/Dankort",
            "prepaid" => false,
            "country" => [
                "numeric" => "208",
                "alpha2" => "DK",
                "name" => "Denmark",
                "emoji" => "🇩🇰",
                "currency" => "DKK",
                "latitude" => 56,
                "longitude" => 19
            ],
            "bank" => [
                "name" => "Jyske Bank",
                "url" => "www.jyskebank.dk",
                "phone" => "+4589893300",
                "city" => "Hjørring",
            ]
        ]);
    }
}
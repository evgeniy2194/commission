<?php

use App\Services\ExchangeRate\ApilayerService;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tests\Traits\MockClientTrait;

class ApilayerServiceTest extends TestCase
{
    use MockClientTrait;

    public function testGetRateByCurrency(): void
    {
        $apilayerService = new ApilayerService($this->getMockClient([
            new Response(200, [], self::getMockData()),
            new Response(200, [], self::getMockData()),
        ]));

        $this->assertEquals(1.09298, $apilayerService->getRateByCurrency('USD'));
        $this->assertEquals(0, $apilayerService->getRateByCurrency('EUR'));
    }

    public function testGetRateByCurrencyExceptions(): void
    {
        $this->expectException(Exception::class);

        $apilayerService = new ApilayerService($this->getMockClient([
            new Response(401, [], json_encode(["message" => "Invalid authentication credentials"]))
        ]));
        $apilayerService->getRateByCurrency('USD');
    }

    public static function getMockData(): string
    {
        return json_encode([
            "success" => true,
            "timestamp" => 1675604823,
            "base" => "EUR",
            "date" => "2023-02-05",
            "rates" => [
                "AED" => 3.977156,
                "AFN" => 98.003476,
                "USD" => 1.09298,
                "JPY" => 1.09298,
                "GBP" => 1.09298
            ]
        ]);
    }
}
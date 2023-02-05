<?php

use App\Services\Commission\CommissionsService;
use App\Services\CountryLocation\BinlistLocationService;
use App\Services\ExchangeRate\ApilayerService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tests\Traits\MockClientTrait;

final class CommissionsServiceTest extends TestCase
{
    use MockClientTrait;

    public function testGetCommissionByCountry()
    {
        $service = $this->getService();

        $this->assertEquals(
            CommissionsService::EU_COMMISSION,
            $service->getCommissionByCountry("NL")
        );
        $this->assertEquals(
            CommissionsService::DEFAULT_COMMISSION,
            $service->getCommissionByCountry("UA")
        );
    }

    public function testGetAmountWithRate()
    {
        $service = $this->getService();

        $this->assertEquals(100, $service->getAmountWithRate(100, 2, 'EUR'));
        $this->assertEquals(100, $service->getAmountWithRate(100, 0, 'USD'));
        $this->assertEquals(50, $service->getAmountWithRate(100, 2, 'USD'));
    }

    public function testGetSumWithCommission()
    {
        $service = new CommissionsService(
            new ApilayerService($this->getMockClient([
                new Response(200, [], ApilayerServiceTest::getMockData()),
                new Response(200, [], ApilayerServiceTest::getMockData()),
            ])),
            new BinlistLocationService($this->getMockClient([
                new Response(200, [], BinlistLocationServiceTest::getMockData()),
                new Response(200, [], BinlistLocationServiceTest::getMockData()),
            ]))
        );

        $this->assertEquals(2, $service->getSumWithCommission(45717360, 100.00, "EUR"));
        $this->assertEquals(0.91, $service->getSumWithCommission(516793, 50.00, "USD"));
    }

    private function getService(): CommissionsService
    {
        return new CommissionsService(
            new ApilayerService($this->getMockClient([])),
            new BinlistLocationService($this->getMockClient([]))
        );
    }
}
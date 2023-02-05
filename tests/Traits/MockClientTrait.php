<?php

namespace Tests\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

trait MockClientTrait
{
    private function getMockClient(array $responses): Client
    {
        $mock = new MockHandler($responses);

        return new Client([
            'handler' => HandlerStack::create($mock)
        ]);
    }
}
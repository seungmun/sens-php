<?php

namespace Sens\Tests;

use Http\Client\Common\HttpMethodsClient;
use PHPUnit\Framework\TestCase;
use Sens\Client;

class ClientTest extends TestCase
{
    public function testCreateClient()
    {
        $client = new Client();

        $this->assertInstanceOf(Client::class, $client);
        $this->assertInstanceOf(HttpMethodsClient::class, $client->getHttpClient());
    }
}

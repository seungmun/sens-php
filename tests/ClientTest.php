<?php

namespace Sens\Tests;

use Http\Client\Common\HttpMethodsClient;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Sens\Auth\Credentials;
use Sens\Client;

class ClientTest extends TestCase
{
    public function testCreateClient()
    {
        $client = new Client();

        $this->assertInstanceOf(Client::class, $client);
        $this->assertInstanceOf(HttpMethodsClient::class, $client->getHttpClient());
    }

    public function testCredentials()
    {
        $credentials = new Credentials('test', 'test');

        $this->assertInstanceOf(Credentials::class, $credentials);
        $this->assertTrue($credentials->validate());
    }

    /*
    public function testSimpleRequest()
    {
        $credentials = new Credentials('test', 'test');

        $url = 'https://sens.apigw.ntruss.com/common/v2/projects';
        $response = (new Client($credentials))->getHttpClient()->get($url, []);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
    */
}

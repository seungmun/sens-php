<?php

namespace Sens\Drivers;

use Sens\Client;
use Sens\HttpClient\Helpers\QueryStringBuilder;
use Sens\HttpClient\Response;

abstract class Driver
{
    /**
     * Sens client instance.
     *
     * @var \Sens\Client
     */
    protected $client;

    /**
     * Create a new driver instance.
     *
     * @param  \Sens\Client  $client
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Send a HTTP Get request.
     *
     * @param  string  $uri
     * @param  array  $params
     * @param  array  $headers
     * @return \Sens\HttpClient\Response
     * @throws \Http\Client\Exception
     */
    protected function get(string $uri, array $params = [], array $headers = [])
    {
        $response = $this->client->getHttpClient()->get(
            self::buildRequestUrl($uri, $params), $headers
        );

        return Response::make($response);
    }

    /**
     * Build request url with the given uri and query data.
     *
     * @param  string  $uri
     * @param  array  $queries
     * @return string
     */
    private static function buildRequestUrl(string $uri, array $queries = [])
    {
        $queryString = QueryStringBuilder::build($queries);

        return "{$uri}?{$queryString}";
    }
}

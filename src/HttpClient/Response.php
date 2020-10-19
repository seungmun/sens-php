<?php

namespace Sens\HttpClient;

use Psr\Http\Message\ResponseInterface;
use Sens\HttpClient\Helpers\JsonSerializer;

class Response
{
    /**
     * Response instance.
     *
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * Create a new Http response instance.
     *
     * @param  \Psr\Http\Message\ResponseInterface  $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * Get the response body.
     *
     * @return array|string
     */
    public function getBody()
    {
        $body = (string) $this->response->getBody();

        if (! empty($body) && $this->getHeader('Content-Type') === Headers::CONTENT_TYPE_JSON) {
            return JsonSerializer::decode($body);
        }

        return $body;
    }

    /**
     * Get the http response code.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    /**
     * Get the headers of the response.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->response->getHeaders();
    }

    /**
     * Get the header of the specified name.
     *
     * @param  string  $name
     * @return string
     */
    public function getHeader(string $name)
    {
        $array = $this->response->getHeader($name);

        return array_shift($array);
    }

    /**
     * Create a new HTtp response instance.
     *
     * @param  \Psr\Http\Message\ResponseInterface  $response
     * @return \Sens\HttpClient\Response
     */
    public static function make(ResponseInterface $response)
    {
        return new self($response);
    }
}

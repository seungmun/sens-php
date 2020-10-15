<?php

namespace Sens\HttpClient\Plugins;

use Http\Client\Common\Plugin\Journal;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ResponseHistory implements Journal
{
    /**
     * The http response.
     *
     * @var \Psr\Http\Message\ResponseInterface|null
     */
    private $response;

    /**
     * Get the response instance.
     *
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Record a successful call.
     *
     * @param  RequestInterface  $request
     * @param  ResponseInterface  $response
     */
    public function addSuccess(RequestInterface $request, ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * Record a failed call.
     *
     * @param  RequestInterface  $request
     * @param  ClientExceptionInterface  $exception
     */
    public function addFailure(RequestInterface $request, ClientExceptionInterface $exception)
    {
        // r.i.p seungmun... (T oT)
    }
}

<?php

namespace Sens\HttpClient\Plugins;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;

class DefaultHeader implements Plugin
{
    /**
     * The http header attributes.
     *
     * @var array
     */
    private $headers = [];

    /**
     * Create a new plugin instance.
     *
     * @param  array  $headers
     * @return void
     */
    public function __construct(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * Handle the request and return the response coming from the next callable.
     *
     * @see http://docs.php-http.org/en/latest/plugins/build-your-own.html
     *
     * @param  \Psr\Http\Message\RequestInterface  $request
     * @param  callable  $next  Next middleware in the chain, the request is passed as the first argument
     * @param  callable  $first  First middleware in the chain, used to to restart a request
     * @return \Http\Promise\Promise
     *
     * @throws \Throwable
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        foreach ($this->headers as $key => $value) {
            if (!$request->hasHeader($key)) {
                $request = $request->withHeader($key, $value);
            }
        }

        return $next($request);
    }
}

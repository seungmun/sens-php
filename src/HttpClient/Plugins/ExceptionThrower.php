<?php

namespace Sens\HttpClient\Plugins;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Sens\Exceptions\RuntimeException;

class ExceptionThrower implements Plugin
{
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
        return $next($request)->then(function (ResponseInterface $response) {
            $status = $response->getStatusCode();

            if ($status >= 400 && $status < 600) {
                throw self::handle($status,
                    ResponseMediator::getErrorMessage($response) ?? $response->getReasonPhrase());
            }

            return $response;
        });
    }

    /**
     * Create an exception from a status code and error message.
     *
     * @param  int  $status
     * @param  string  $message
     * @return \Sens\Exceptions\RuntimeException
     */
    protected static function handle(int $status, string $message = '')
    {
        return new RuntimeException($message, $status);
    }
}

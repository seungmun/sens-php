<?php

namespace Sens\HttpClient\Plugins;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;
use Sens\Auth\Credentials;
use Sens\Auth\Signature;
use Sens\Auth\Timestamp;

class Authentication implements Plugin
{
    /**
     * The credentials instance.
     *
     * @var \Sens\Auth\Credentials
     */
    private $credentials;

    /**
     * Create a new authentication plugin instance.
     *
     * @param  \Sens\Auth\Credentials  $credentials
     */
    public function __construct(Credentials $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * Handle the request and return the response coming from the next callable.
     *
     * @see http://docs.php-http.org/en/latest/plugins/build-your-own.html
     *
     * @param  \Psr\Http\Message\RequestInterface  $request
     * @param  callable  $next Next middleware in the chain, the request is passed as the first argument
     * @param  callable  $first First middleware in the chain, used to to restart a request
     * @return \Http\Promise\Promise
     *
     * @throws \Throwable
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        /**
         * The elapsed time from January 1, 1970 00:00:00 Coordinated Universal Time (UTC), expressed in milliseconds.
         * If the time difference from the API Gateway server is more than 5 minutes, the request is considered invalid.
         *
         * Usually, it takes only a few seconds from creation of this timestamp to processing time of the request,
         * so it is not a problem even if the logic is separated and processed.
         */
        $timestamp = new Timestamp();
        $signature = new Signature($request, $this->credentials, $timestamp);

        $request->withHeader('x-ncp-iam-access-key', $signature->getCredentials()->getAccessKey());
        $request->withHeader('x-ncp-apigw-timestamp', (string) $signature->getTimestamp());
        $request->withHeader('x-ncp-apigw-signature-v2', (string) $signature);

        return $next($request);
    }
}

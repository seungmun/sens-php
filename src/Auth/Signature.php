<?php

namespace Sens\Auth;

use Psr\Http\Message\RequestInterface;

class Signature
{
    /**
     * The current request instance.
     *
     * @var \Psr\Http\Message\RequestInterface
     */
    protected $request;

    /**
     * The credentials instance.
     *
     * @var \Sens\Auth\Credentials
     */
    protected $credentials;

    /**
     * The timestamp instance.
     *
     * @var \Sens\Auth\Timestamp
     */
    protected $timestamp;

    /**
     * Create a new signature instance.
     *
     * @param  \Psr\Http\Message\RequestInterface  $request
     * @param  \Sens\Auth\Credentials  $credentials
     * @param  \Sens\Auth\Timestamp  $timestamp
     */
    public function __construct(RequestInterface $request, Credentials $credentials, Timestamp $timestamp)
    {
        $this->request = $request;
        $this->credentials = $credentials;
        $this->timestamp = $timestamp;
    }

    /**
     * Get the request instance.
     *
     * @return \Psr\Http\Message\RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set the request instance.
     *
     * @param  \Psr\Http\Message\RequestInterface  $request
     * @return \Sens\Auth\Signature
     */
    public function setRequest(RequestInterface $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get the credentials instance.
     *
     * @return \Sens\Auth\Credentials
     */
    public function getCredentials()
    {
        return $this->credentials;
    }

    /**
     * Set the credentials instance.
     *
     * @param  \Sens\Auth\Credentials  $credentials
     * @return \Sens\Auth\Signature
     */
    public function setCredentials(Credentials $credentials)
    {
        $this->credentials = $credentials;

        return $this;
    }

    /**
     * Get the timestamp instance.
     *
     * @return \Sens\Auth\Timestamp
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set the timestamp instance.
     *
     * @param  \Sens\Auth\Timestamp  $timestamp
     * @return \Sens\Auth\Signature
     */
    public function setTimestamp(Timestamp $timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Make a sign-able signature data.
     *
     * @return string
     */
    public function sign()
    {
        $path = $this->request->getUri()->getPath();

        if ($query = $this->request->getUri()->getQuery()) {
            $path .= '?'.$query;
        }

        $clues = [
            strtoupper($this->request->getMethod()).' '.$path,
            $this->timestamp->getTimestamp(),
            $this->credentials->getAccessKey(),
        ];

        $secretKey = utf8_encode($this->credentials->getSecretKey());
        $data = utf8_encode(implode("\n", $clues));
        $hash = hex2bin(hash_hmac('sha256', $data, $secretKey));

        return base64_encode($hash);
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->sign();
    }
}

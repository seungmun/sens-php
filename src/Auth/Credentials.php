<?php

namespace Sens\Auth;

class Credentials
{
    /**
     * Ncloud api access key.
     *
     * @var string
     */
    protected $accessKey;

    /**
     * Ncloud api secret key.
     *
     * @var string
     */
    protected $secretKey;

    /**
     * Create a new credentials instance.
     *
     * @param  string  $accessKey
     * @param  string  $secretKey
     */
    public function __construct(string $accessKey, string $secretKey)
    {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
    }

    /**
     * Get the access key attribute.
     *
     * @return string
     */
    public function getAccessKey()
    {
        return $this->accessKey;
    }

    /**
     * Set the access key attribute.
     *
     * @param  string  $accessKey
     * @return \Sens\Auth\Credentials
     */
    public function setAccessKey(string $accessKey)
    {
        $this->accessKey = $accessKey;

        return $this;
    }

    /**
     * Get the secret key attribute.
     *
     * @return string
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * Get the access key attribute.
     *
     * @param  string  $secretKey
     * @return \Sens\Auth\Credentials
     */
    public function setSecretKey(string $secretKey)
    {
        $this->secretKey = $secretKey;

        return $this;
    }
}

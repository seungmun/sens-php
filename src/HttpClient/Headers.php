<?php

namespace Sens\HttpClient;

interface Headers
{
    /**
     * The base url.
     */
    public const BASE_URL = 'https://sens.apigw.ntruss.com';

    /**
     * The user agent.
     *
     * @var string
     */
    public const USER_AGENT = 'seungmun-sens-php-client/1.0';

    /**
     * The json content type.
     *
     * @var string
     */
    public const CONTENT_TYPE_JSON = 'application/json; charset=utf-8';
}

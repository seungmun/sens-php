<?php

namespace Sens\HttpClient;

interface Headers
{
    /**
     * The base url.
     */
    public const BASE_URL = 'https://sens.apigw.ntruss.com/common/v2';

    /**
     * The user agent.
     *
     * @var string
     */
    public const USER_AGENT = 'seungmun-sens-php-client/1.0';
}

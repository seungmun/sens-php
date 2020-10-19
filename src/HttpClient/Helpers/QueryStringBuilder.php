<?php

namespace Sens\HttpClient\Helpers;

class QueryStringBuilder
{
    /**
     * Encode a query string.
     *
     * @param  array  $queries
     * @return string
     */
    public static function build(array $queries = [])
    {
        if (count($queries) < 1) return '';
        $queries = http_build_query($queries, '', '&', PHP_QUERY_RFC3986);

        return $queries;
    }
}

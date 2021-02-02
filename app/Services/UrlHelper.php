<?php

if (! function_exists('custom_route')) {
    /**
     * rewrite url scheme on env var .
     * Example of using: {{ custom_route('login') }}
     *
     * @param  string  $url
     * @param  object|array|null  $params
     * @return string
     */
    function custom_route(string $url, $params = null)
    {
        return preg_replace('/https?/i', env('APP_SCHEME'), route($url, $params));
    }
}

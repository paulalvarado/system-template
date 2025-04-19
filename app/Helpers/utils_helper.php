<?php
if (!function_exists('assets')) {
    /**
     * Return the base URL for the assets, with an optional route.
     *
     * @param string $route
     * @return string
     */
    function assets($route = '')
    {
        return base_url('assets/' . $route);
    }
}

if (!function_exists('base_url_api')) {
    /**
     * Return the base URL for the API, with an optional route.
     *
     * @param string $ruta
     * @return string
     * @throws InvalidArgumentException
     */
    function base_url_api($ruta = '')
    {
        if (!is_string($ruta)) {
            throw new InvalidArgumentException('Ruta must be a string');
        }

        return base_url('api/' . ltrim($ruta, '/'));
    }
}

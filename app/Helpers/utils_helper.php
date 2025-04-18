<?php
if (!function_exists('assets')) {
    function assets($route = '')
    {
        return base_url('assets/' . $route);
    }
}
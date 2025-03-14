<?php

if (!function_exists('getStatusCode')) {
    function getStatusCode($request)
    {
        return !$request->validated() ? 422 : 404;
    }
}

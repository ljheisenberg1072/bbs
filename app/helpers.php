<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

if (!function_exists('route_class')) {
    function route_class()
    {
        return str_replace('.', '-', Route::currentRouteName());
    }
}

if (!function_exists('active_class')) {
    function active_class($route, $param = null, $class='active')
    {
        if($param != null) {
            $data = explode(".", $route);
            $model = Str::singular($data[0]);
            return request()->routeIs($route) && request()->route($model)->id == $param ? $class : '';
        }

        return request()->routeIs($route) ? $class : '';
    }
}

if (!function_exists('make_excerpt')) {
    function make_excerpt($value, $length = 200)
    {
        $excerpt = trim(preg_replace('/\r\n|\r|\n+/', '', strip_tags($value)));
        return Str::limit($excerpt, $length);
    }
}

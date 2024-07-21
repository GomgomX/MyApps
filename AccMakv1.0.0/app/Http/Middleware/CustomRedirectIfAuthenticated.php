<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated as BaseRedirectIfAuthenticated;

class CustomRedirectIfAuthenticated extends BaseRedirectIfAuthenticated
{
    protected function defaultRedirectUri(): string
    {
        foreach (['dashboard', 'home'] as $uri) {
            if (Route::has($uri)) {
                return route($uri);
            }
        }

        $routes = Route::getRoutes()->getRoutesByMethod()['GET'];

        foreach (['dashboard', 'home'] as $uri) {
            if (isset($routes[$uri])) {
                return '/' . $uri;
            }
        }

        request()->session()->put('url.intended', request()->fullUrl());
        return '/account/accountmanagement/logoutaccount';
    }
}
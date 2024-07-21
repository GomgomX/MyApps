<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CustomRedirectIfAuthenticated;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
	->withMiddleware(function (Middleware $middleware) {
		//$middleware->trustHosts(at: ['laravel.test'], subdomains: false);
		$middleware->alias([
            'custom.guest' => CustomRedirectIfAuthenticated::class,
		])->validateCsrfTokens(except: [
		    'shop/buypoints/paypal/ipn', 'shop/buypoints/stripe/webhook'
		]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
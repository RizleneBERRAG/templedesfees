<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use App\Http\Middleware\EntetesSecurite;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(EntetesSecurite::class);

        /*
         * Stripe appelle le webhook de serveur a serveur : il n'a pas de
         * session, donc pas de jeton CSRF a presenter. La route est protegee
         * autrement, et bien mieux : chaque notification porte une signature
         * cryptographique verifiee dans la Caisse.
         */
        $middleware->validateCsrfTokens(except: [
            'paiement/notification',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

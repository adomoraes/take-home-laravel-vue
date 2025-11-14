<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php', // Já tínhamos corrigido isto
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // --- ESTA É A CORREÇÃO ---
        // Diga ao Laravel para NÃO verificar o token CSRF
        // em qualquer rota que comece com 'api/'
        $middleware->validateCsrfTokens(except: [
            'api/*'
        ]);
        // -------------------------

    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ...
    })->create();

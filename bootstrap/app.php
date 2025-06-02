<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request; // <--- ¡ASEGÚRATE DE AÑADIR ESTA LÍNEA!

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Configuración para confiar en los proxies (como ngrok)
        $middleware->trustProxies(
            '*', // Confía en cualquier IP de proxy
            Request::HEADER_X_FORWARDED_FOR |
            Request::HEADER_X_FORWARDED_HOST |
            Request::HEADER_X_FORWARDED_PORT |
            Request::HEADER_X_FORWARDED_PROTO |
            Request::HEADER_X_FORWARDED_AWS_ELB // O puedes usar Request::HEADER_X_FORWARDED_ALL
        );

        // Aquí puedes agregar o configurar otros middlewares si es necesario
        // Ejemplo:
        // $middleware->validateCsrfTokens(except: [
        //     'stripe/*',
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
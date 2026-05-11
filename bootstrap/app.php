<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // AQUI ESTÁ A MÁGICA: Registrando o apelido do nosso middleware
        $middleware->alias([
            'tipo' => \App\Http\Middleware\VerificaTipo::class,
            'membro' => \App\Http\Middleware\SomenteMembro::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

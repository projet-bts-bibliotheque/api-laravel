<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\ForceJsonHeader;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api/index.php',
        health: '/status',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(ForceJsonHeader::class);
        $middleware->alias([
            'isStaff' => \App\Http\Middleware\IsStaff::class,
            'isAdmin' => \App\Http\Middleware\IsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

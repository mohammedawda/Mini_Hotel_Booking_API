<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Illuminate\Foundation\Configuration\Exceptions $exceptions) {
        $exceptions->renderable(function (Throwable $e) {
            if (is_string($e->getCode()) || $e->getCode() == 0) {
                return response()->json(["status" => false, "message" => __('An unexpected error occurred, Please try again later.'), "debug" => $e->__toString()], 500);
            }

            return response()->json(["status" => false, "message" => $e->getMessage(), "debug" => \Illuminate\Support\Facades\App::environment(['production']) ? "" : $e->__toString()], $e->getCode());
        });
    })->create();

<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's route middleware.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        // ... otros middlewares existentes
        
        'auth' => \App\Http\Middleware\AuthMiddleware::class,
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        
        // ... resto de middlewares
    ];
}
<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     */
    protected $middleware = [
        // Otros middlewares globales aquí


    ];

    /**
     * The application's route middleware groups.
     */
    protected $middlewareGroups = [
        'web' => [
            // Middlewares para rutas web

            
        ],

        'api' => [
            // Middlewares para rutas API
        ],
    ];

    /**
     * The application's route middleware.
     */
    protected $routeMiddleware = [
        // Otros middlewares específicos de rutas aquí
    ];
}

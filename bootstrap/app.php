<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Алиас для твоего admin middleware
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        // Куда редиректить гостя, если полез в защищённый роут
        $middleware->redirectGuestsTo(fn () => route('login'));

        // Куда редиректить уже авторизованного, если он полез на /login или /register (guest middleware)
        $middleware->redirectUsersTo(function (Request $request) {
            $user = $request->user();

            if ($user && (bool) $user->is_admin) {
                return route('products.index'); // /admin/products
            }

            return route('catalog.index'); // /catalog
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();

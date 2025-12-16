<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Проверяем авторизацию и права админа в одной строке
        if (! $request->user()?->is_admin) {
            abort(403, 'Доступ запрещён. Требуются права администратора.');
        }

        return $next($request);
    }
}

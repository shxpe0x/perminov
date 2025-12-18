<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Необходимо войти в систему');
        }

        if (!auth()->user()->is_admin) {
            abort(403, 'У вас нет прав доступа к админ-панели');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // auth middleware должен стоять раньше, но на всякий:
        if (! $request->user()) {
            abort(403);
        }

        if (! (bool) $request->user()->is_admin) {
            abort(403);
        }

        return $next($request);
    }
}

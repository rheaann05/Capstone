<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsTenantAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->hasAnyRole(['admin', 'super-admin'])) { 
            return $next($request);
        }

        abort(403, 'Unauthorized access.');
    }
}
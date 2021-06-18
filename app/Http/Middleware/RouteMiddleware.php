<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RouteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        abort_unless(auth()->check() && auth()->user()->id_jabatan == $role, 403, "You don't have permissions to access this area");
        return $next($request);
    }
}

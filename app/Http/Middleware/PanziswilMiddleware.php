<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Role;

class PanziswilMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Role::where('id_users', auth()->user()->id)->get()->toArray();

        foreach ($user as $role) {
            if ($role['id_jabatan'] == 1) {
                return $next($request);
            }
        }
        
        foreach ($user as $role) {
            if ($role['id_jabatan'] != 1) {
                abort(403, 'Otorisasi Gagal!');
            }
        }
    }
}

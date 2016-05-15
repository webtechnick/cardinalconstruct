<?php

namespace App\Http\Middleware;

use Closure;

class RequireAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guest() || Auth::user('role') != 'admin') {
            flash('Admin access is required.');
            return redirect('/');
        }
        return $next($request);
    }
}

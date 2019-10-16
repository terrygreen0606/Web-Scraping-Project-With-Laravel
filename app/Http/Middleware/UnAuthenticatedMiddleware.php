<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;


/**
 * 
 * 
 * This middlware allow unauthenticated users to access to routes
 * 
 */

class UnAuthenticatedMiddleware
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

        $user = Auth::user();

        if ($user != null) {
            return redirect(route('dashboard.index'));
        }

        return $next($request);
    }
}

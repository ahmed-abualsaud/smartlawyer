<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ManagementPermission
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
        if(!Auth::check() && Auth::user()->role != 'admin'){
            flash(__('dashboard.please_use_our_app'));
            return redirect('/');
        }
        return $next($request);
    }
}

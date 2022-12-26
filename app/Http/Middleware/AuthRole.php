<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class AuthRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]|null  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {

        if (!Auth::check()) {
            return redirect()->route("auth.login");
        }

        foreach ($roles as $role) {

            if ($role == "super-admin" && Auth::user()->isSuperAdminAccount()) {
                return $next($request);
            }

            if ($role == "manager" && Auth::user()->isManagerAccount()) {
                return $next($request);
            }
            if ($role == "counter" && Auth::user()->isCounterAccount()) {
                return $next($request);
            }


            if ($role == "user" && Auth::user()->isUserAccount()) {
                return $next($request);
            }
        }
        abort(403);
    }
}

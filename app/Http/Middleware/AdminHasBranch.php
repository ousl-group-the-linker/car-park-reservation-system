<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AdminHasBranch
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]|null  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->isUserAccount()) {
            return $next($request);
        }

        if (!Auth::check()) {
            return redirect()->route("auth.login");
        }

        if(Route::is("branches-management")){
            return $next($request);
        }

        if (Auth::user()->isSuperAdminAccount()) {
            return $next($request);
        }
        if (Auth::user()->isManagerAccount() && Auth::user()->ManageBranch !== null) {
            return $next($request);
        }
        if (Auth::user()->isCounterAccount() && Auth::user()->WorkForBranch !== null) {
            return $next($request);
        }


        return redirect()->route("branches-management");
    }
}

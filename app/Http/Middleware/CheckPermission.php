<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, ...$permissions)
    {
        if (!Auth::check()) {
            // Redirect to the login page if the user is not authenticated
            return redirect()->route('login');
        }

        foreach ($permissions as $permission) {
            if (Auth::user()->hasPermission($permission)) {
                // Proceed if the user has at least one of the required permissions
                return $next($request);
            }
        }

        // Abort with a 403 status if the user does not have any of the required permissions
        return redirect()->route('login');
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                $roleUser = RoleUser::where('user_id', $user->id)->first();

                if ($roleUser) {
                    if ($user->status === 'active') {
                        switch ($roleUser->role_id) {
                            case 1:
                                return redirect()->intended(RouteServiceProvider::CUSTOMER);
                            case 2:
                                return redirect()->intended(RouteServiceProvider::DISTRIBUTOR);
                            default:
                                return redirect()->intended(RouteServiceProvider::HOME);
                        }
                    } else {
                        Auth::guard($guard)->logout();
                        return redirect()->route('login')->withErrors(['email' => 'Your account is inactive.']);
                    }
                }
            }
        }

        return $next($request);
    }
}

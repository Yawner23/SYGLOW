<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\RoleUser;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Redirect;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed
            $user = Auth::user();

            // Find the user's role and status
            $roleUser = RoleUser::where('user_id', $user->id)->first();

            if ($roleUser) {
                if ($user->status === 'active') {
                    // Redirect based on role_id
                    switch ($roleUser->role_id) {
                        case 1:
                            return redirect()->intended(RouteServiceProvider::CUSTOMER);
                        case 2:
                            return redirect()->intended(RouteServiceProvider::DISTRIBUTOR);
                        default:
                            return redirect()->intended(RouteServiceProvider::HOME); // Default redirect if no match
                    }
                } else {
                    // Status is inactive
                    Auth::logout(); // Ensure the user is logged out if status is inactive
                    return Redirect::back()->withErrors(['email' => 'Your account is inactive.']);
                }
            }
        }
        return Redirect::back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

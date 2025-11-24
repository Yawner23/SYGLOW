<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use App\Models\User;
use App\Mail\OtpMail;
use App\Models\Customer;
use App\Models\RoleUser;
use Illuminate\View\View;
use App\Models\Distributor;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use App\Models\ReferralCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $commonRules = [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        if ($request->role === 'customer') {
            $request->validate(array_merge($commonRules, [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'contact_number' => ['required', 'string', 'max:255'],
                'referral_code' => ['nullable', 'string', 'max:255'],
            ]));

            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'status' => 'inactive',
                'password' => Hash::make($request->password),
                'email_verified_at' => null,
            ]);

            Customer::create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'contact_number' => $request->contact_number,
                'referral_code' => $request->referral_code,
            ]);

            RoleUser::create([
                'user_id' => $user->id,
                'role_id' => 1,
            ]);

            // Generate and store OTP for customers only
            $otp = Str::random(4);
            $request->session()->put('otp', $otp);
            $request->session()->put('user_id', $user->id);

            Mail::to($user->email)->send(new OtpMail($otp));

            return redirect()->route('verify-otp');
        } elseif ($request->role === 'admin') {
            $request->validate(array_merge($commonRules, [
                'name' => ['required', 'string', 'max:255'],
            ]));


            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'status' => 'active',
                'password' => Hash::make($request->password),
                'email_verified_at' => null,
            ]);

            RoleUser::create([
                'user_id' => $user->id,
                'role_id' => 3,
            ]);
            Auth::login($user);
            return redirect(RouteServiceProvider::HOME);
        } elseif ($request->role === 'distributor') {
            $request->validate([
                'distributor_type' => ['required', 'string'],
                'region' => ['required', 'string', 'max:255'],
                'province' => ['required', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:255'],
                'brgy' => ['required', 'string', 'max:255'],
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'max:255'],
                'contact_number' => ['required', 'string', 'max:255'],
                'code' => ['required', 'string', 'max:255'],
                'valid_id_path' => ['required', 'file', 'mimes:jpeg,png,pdf', 'max:2048'],
                'photo_with_background_path' => ['required', 'file', 'mimes:jpeg,png', 'max:2048'],
                'selfie_with_id_path' => ['required', 'file', 'mimes:jpeg,png', 'max:2048'],
            ], [
                'code.exists' => 'The code provided does not exist in our records.',
            ]);

            $validIdDirectory = public_path('uploads/valid_ids');
            $photoBackgroundDirectory = public_path('uploads/photo_backgrounds');
            $selfieDirectory = public_path('uploads/selfies');

            // Handle valid ID upload
            if ($request->hasFile('valid_id_path')) {
                $validIdFile = $request->file('valid_id_path');
                $validIdName = uniqid() . '.' . $validIdFile->getClientOriginalExtension();
                $validIdFile->move($validIdDirectory, $validIdName);
                $validIdPath = 'uploads/valid_ids/' . $validIdName;
            }

            // Handle photo with background upload
            if ($request->hasFile('photo_with_background_path')) {
                $photoWithBackgroundFile = $request->file('photo_with_background_path');
                $photoWithBackgroundName = uniqid() . '.' . $photoWithBackgroundFile->getClientOriginalExtension();
                $photoWithBackgroundFile->move($photoBackgroundDirectory, $photoWithBackgroundName);
                $photoWithBackgroundPath = 'uploads/photo_backgrounds/' . $photoWithBackgroundName;
            }

            // Handle selfie with ID upload
            if ($request->hasFile('selfie_with_id_path')) {
                $selfieWithIdFile = $request->file('selfie_with_id_path');
                $selfieWithIdName = uniqid() . '.' . $selfieWithIdFile->getClientOriginalExtension();
                $selfieWithIdFile->move($selfieDirectory, $selfieWithIdName);
                $selfieWithIdPath = 'uploads/selfies/' . $selfieWithIdName;
            }

            $tempPassword = Str::random(10);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'status' => 'pending',
                'password' => Hash::make($tempPassword),
                'email_verified_at' => null,
            ]);

            // Validate 'code' based on distributor_type
            if ($request->distributor_type == 1) {
                // distributor_type 1: code must exist in referral_code table
                $codeExists = ReferralCode::where('referral_code', $request->code)->exists();
                if (!$codeExists) {
                    return redirect()->back()
                        ->withInput() // Keep the input in the form
                        ->withErrors(['code' => 'Referral Code does not exist']);
                }
            } elseif ($request->distributor_type == 2 || $request->distributor_type == 3) {
                // distributor_type != 1: code must match a user_id in distributors table
                $codeExists = Distributor::where('distributor_type', '1')->where('user_id', $request->code)->exists();
                if (!$codeExists) {
                    return redirect()->back()
                        ->withInput() // Keep the input in the form
                        ->withErrors(['code' => 'Code does not match any existing distributor user_id']);
                }
            } else {
                $codeExists = Distributor::where('distributor_type', '3')->where('user_id', $request->code)->exists();
                if (!$codeExists) {
                    return redirect()->back()
                        ->withInput() // Keep the input in the form
                        ->withErrors(['code' => 'Code does not match any existing City Distributor']);
                }
            }

            Distributor::create([
                'user_id' => $user->id,
                'distributor_type' => $request->distributor_type,
                'region' => $request->region,
                'province' => $request->province,
                'city' => $request->city,
                'brgy' => $request->brgy,
                'contact_number' => $request->contact_number,
                'name' => $request->name,
                'code' =>  $request->code,
                'valid_id_path' => $validIdPath ?? null,
                'photo_with_background_path' => $photoWithBackgroundPath ?? null,
                'selfie_with_id_path' => $selfieWithIdPath ?? null,
            ]);

            RoleUser::create([
                'user_id' => $user->id,
                'role_id' => 2,
            ]);

            // Redirect to success page with the temporary email and password
            return redirect(RouteServiceProvider::SUCCESS)->with([
                'email' => $user->email,
                'tempPassword' => $tempPassword,
            ]);
        } else {
            return redirect()->back()->withErrors(['role' => 'Invalid role specified.']);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PasswordController extends Controller
{
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();
        $otp = rand(1000, 9999); // Generate a 4-digit OTP

        // Store OTP in session or database
        session(['otp' => $otp, 'email' => $request->email]);

        // Send OTP to user's email
        Mail::to($request->email)->send(new OtpMail($otp));

        return redirect('/otp');
    }

    public function showOtpForm()
    {
        return view('otp');
    }

    public function verifyOtp(Request $request)
    {
        // Validate each OTP input field
        $request->validate([
            'otp1' => 'required|numeric|digits:1',
            'otp2' => 'required|numeric|digits:1',
            'otp3' => 'required|numeric|digits:1',
            'otp4' => 'required|numeric|digits:1',
        ]);

        // Combine OTP inputs into a single string
        $otp = $request->otp1 . $request->otp2 . $request->otp3 . $request->otp4;

        // Compare the combined OTP with the session OTP
        if ($otp == session('otp')) {
            // Redirect to password reset form if OTP is correct
            return redirect('/reset_password');
        }

        // Return with an error if OTP is incorrect
        return back()->withErrors(['otp' => 'Invalid OTP']);
    }


    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::where('email', session('email'))->first();
        $user->password = bcrypt($request->password);
        $user->save();

        session()->forget(['otp', 'email']);

        return redirect('/login')->with('status', 'Password reset successfully!');
    }
}

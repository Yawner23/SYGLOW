<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;

class VerificationController extends Controller
{
    public function showOtpForm()
    {
        return view('verify-otp'); // This is the view with the OTP form
    }

    public function verifyOtp(Request $request)
    {
        // Concatenate OTP inputs
        $otp = $request->input('otp1') . $request->input('otp2') . $request->input('otp3') . $request->input('otp4');

        // Validate the concatenated OTP
        $validator = Validator::make(['otp' => $otp], [
            'otp' => ['required', 'size:4'], // Ensure OTP length is exactly 4
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Retrieve the stored OTP and user ID from the session
        $storedOtp = $request->session()->get('otp');
        $userId = $request->session()->get('user_id');

        // Compare the provided OTP with the stored OTP
        if ($otp === $storedOtp) {
            // Update user to mark email as verified
            $user = User::find($userId);
            if ($user) {
                $user->email_verified_at = now();
                $user->status = 'active';
                $user->save();
            }

            // Clear OTP session data
            $request->session()->forget(['otp', 'user_id']);

            return redirect(RouteServiceProvider::SUCCESS);
        } else {
            return redirect()->back()->withErrors(['otp' => 'Invalid OTP']);
        }
    }

    public function send_otp(Request $request)
    {
        $request->validate([
            'email_address' => 'required|email',
            // Add other validation rules as needed
        ]);

        // Generate OTP
        $otp = Str::random(6); // You can use a more secure method to generate OTPs

        // Save OTP to the database or session for later verification
        $request->session()->put('otp', $otp);
        $request->session()->put('email_address', $request->email_address);

        // Send OTP to the email
        Mail::to($request->email_address)->send(new OtpMail($otp));

        // Return a response to open the modal
        return response()->json(['success' => true]);
    }
    public function verify_otp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string',
        ]);

        $sessionOtp = $request->session()->get('otp');
        $emailAddress = $request->session()->get('email_address');

        if ($request->otp === $sessionOtp) {
            // OTP verified, proceed with further actions
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\SocialMediaAccounts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function edit_profile()
    {
        return view('edit_profile');
    }
    public function update_customer(Request $request)
    {

        $user = Auth::user();

        // Update the customer profile picture if a new one is uploaded
        if ($request->hasFile('profile_picture')) {
            // Define the upload path
            $uploadPath = public_path('uploads/profile_pictures');

            // Create the directory if it does not exist
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Delete old profile picture if it exists
            $oldProfilePicture = $user->customer->profile_picture;
            if ($oldProfilePicture && file_exists($uploadPath . '/' . $oldProfilePicture)) {
                unlink($uploadPath . '/' . $oldProfilePicture);
            }

            // Store the new profile picture
            $file = $request->file('profile_picture');
            $profilePictureName = time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $profilePictureName);

            // Update the profile picture path in the database
            if ($user->customer) {
                $user->customer->update([
                    'profile_picture' => $profilePictureName, // Save only the file name
                ]);
            } else {
                return redirect()->back()->withErrors(['error' => 'Customer profile not found.']);
            }
        }
        // Update the customer and user details
        if ($user->customer) {
            $user->customer->update([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'contact_number' => $request->input('contact_number'),
                'date_of_birth' => $request->input('year') . '-' . $request->input('month') . '-' . $request->input('day'),
            ]);
        } else {
            // Handle case where customer record does not exist
            return redirect()->back()->withErrors(['error' => 'Customer profile not found.']);
        }

        // Update the user details
        $user->update([
            'email' => $request->input('email'),
            'password' => $request->filled('password') ? Hash::make($request->input('password')) : $user->password,
        ]);

        // Check if social media accounts exist
        if ($user->socialMediaAccounts) {
            // Update existing social media accounts
            $user->socialMediaAccounts->update([
                'facebook' => $request->input('facebook'),
                'instagram' => $request->input('instagram'),
                'tiktok' => $request->input('tiktok'),
            ]);
        } else {
            // Create new social media accounts
            $user->socialMediaAccounts()->create([
                'facebook' => $request->input('facebook'),
                'instagram' => $request->input('instagram'),
                'tiktok' => $request->input('tiktok'),
            ]);
        }

        // Redirect back with success message
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }


    public function update_distributor(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $distributor = $user->distributor;





        if ($request->hasFile('profile_picture')) {
            // Define the upload path
            $uploadPath = public_path('uploads/profile_pictures');

            // Create the directory if it does not exist
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Delete old profile picture if it exists
            $oldProfilePicture = $user->distributor->profile_picture;
            if ($oldProfilePicture && file_exists($uploadPath . '/' . $oldProfilePicture)) {
                unlink($uploadPath . '/' . $oldProfilePicture);
            }

            // Store the new profile picture
            $file = $request->file('profile_picture');
            $profilePictureName = time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $profilePictureName);

            // Update the profile picture path in the database
            if ($user->distributor) {
                $user->distributor->update([
                    'profile_picture' => $profilePictureName, // Save only the file name
                ]);
            } else {
                return redirect()->back()->withErrors(['error' => 'Customer profile not found.']);
            }
        }
        
        
        // Define upload directories
        $validIdDirectory = public_path('uploads/valid_ids');
        $photoBackgroundDirectory = public_path('uploads/photo_backgrounds');
        $selfieDirectory = public_path('uploads/selfies');

        // Handle valid ID upload
        if ($request->hasFile('valid_id_path')) {
            // Remove old file if exists
            if ($distributor->valid_id_path && file_exists($validIdDirectory . '/' . $distributor->valid_id_path)) {
                unlink($validIdDirectory . '/' . $distributor->valid_id_path);
            }

            $validIdFile = $request->file('valid_id_path');
            $validIdName = uniqid() . '.' . $validIdFile->getClientOriginalExtension();
            $validIdFile->move($validIdDirectory, $validIdName);
            $distributor->valid_id_path = 'uploads/valid_ids/' . $validIdName;
        }

        // Handle photo with background upload
        if ($request->hasFile('photo_with_background_path')) {
            // Remove old file if exists
            if ($distributor->photo_with_background_path && file_exists($photoBackgroundDirectory . '/' . $distributor->photo_with_background_path)) {
                unlink($photoBackgroundDirectory . '/' . $distributor->photo_with_background_path);
            }

            $photoWithBackgroundFile = $request->file('photo_with_background_path');
            $photoWithBackgroundName = uniqid() . '.' . $photoWithBackgroundFile->getClientOriginalExtension();
            $photoWithBackgroundFile->move($photoBackgroundDirectory, $photoWithBackgroundName);
            $distributor->photo_with_background_path = 'uploads/photo_backgrounds/' . $photoWithBackgroundName;
        }

        // Handle selfie with ID upload
        if ($request->hasFile('selfie_with_id_path')) {
            // Remove old file if exists
            if ($distributor->selfie_with_id_path && file_exists($selfieDirectory . '/' . $distributor->selfie_with_id_path)) {
                unlink($selfieDirectory . '/' . $distributor->selfie_with_id_path);
            }

            $selfieWithIdFile = $request->file('selfie_with_id_path');
            $selfieWithIdName = uniqid() . '.' . $selfieWithIdFile->getClientOriginalExtension();
            $selfieWithIdFile->move($selfieDirectory, $selfieWithIdName);
            $distributor->selfie_with_id_path = 'uploads/selfies/' . $selfieWithIdName;
        }

        // Update distributor data
        $distributor->name = $request->input('name');
        $distributor->region = $request->input('region');
        $distributor->province = $request->input('province');
        $distributor->brgy = $request->input('brgy');
        $distributor->city = $request->input('city');
        $distributor->contact_number = $request->input('contact_number');

        // Update user data
        $user->email = $request->input('email');
        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        // Update social media accounts
        $socialMediaData = [];
        if ($request->filled('facebook')) {
            $socialMediaData['facebook'] = $request->input('facebook');
        }
        if ($request->filled('instagram')) {
            $socialMediaData['instagram'] = $request->input('instagram');
        }
        if ($request->filled('tiktok')) {
            $socialMediaData['tiktok'] = $request->input('tiktok');
        }

        if (!empty($socialMediaData)) {
            $user->socialMediaAccounts()->updateOrCreate(
                ['user_id' => $user->id],
                $socialMediaData
            );
        }

        $user->save();
        $distributor->save();

        return redirect()->back()->with('success', 'Profile updated successfully');
    }
}

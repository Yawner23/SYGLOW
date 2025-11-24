@extends('layouts.sidebar')

@section('title', 'Home')

@section('content')
<div class="flex flex-col justify-between p-8 text-4xl md:flex-row">
    <h1>Edit Profile</h1>
    <h1>Welcome, {{ Auth::user()->distributor->name }}!</h1>
</div>

<form action="{{ route('profile.update_distributor', Auth::user()->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="flex flex-col items-center gap-8 p-8 md:flex-row">
        <img class="w-[7rem] h-[7rem] shrink-0 inline-block rounded-full object-cover"
            src="{{ asset('uploads/profile_pictures/' . (Auth::user()->distributor->profile_picture ?? 'default-avatar.jpg')) }}"
            alt="Profile Picture">

        <div class="flex flex-col">
            <div>
                <input type="file" id="profile_picture" name="profile_picture" class="hidden" />
                <label for="profile_picture" class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg w-40 py-2 text-white border-2 border-white cursor-pointer block text-center">
                    CHANGE PROFILE
                </label>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8 p-8 lg:gap-20 md:grid-cols-2">
        <div class="flex flex-col gap-4">
            <div class="flex flex-col">
                <label for="name">Name</label>
                <input class="w-full p-4 h-full border-[#f56e98] rounded-lg" type="text" name="name" id="name" value="{{ old('name', Auth::user()->distributor->name) }}">
            </div>

            <div class="flex flex-col">
                <label for="email">Email</label>
                <input class="w-full p-4 h-full border-[#f56e98] rounded-lg" type="text" name="email" id="email" value="{{ old('email', Auth::user()->email) }}">
            </div>

            <div class="flex flex-col">
                <label for="contact_number">Contact Number</label>
                <input class="w-full p-4 h-full border-[#f56e98] rounded-lg" type="text" name="contact_number" id="contact_number" value="{{ old('contact_number', Auth::user()->distributor->contact_number) }}">
            </div>

            <div class="flex flex-col">
                <label for="password">Password</label>
                <input class="w-full p-4 h-full border-[#f56e98] rounded-lg" type="password" name="password" id="password" placeholder="Leave empty to keep current password">
            </div>

            <div class="flex flex-col gap-4">
                <label for="valid_id_path" class="flex items-center space-x-4 font-bold cursor-pointer">
                    <i class='p-2 text-5xl text-gray-400 bg-white border-2 bx bxs-image'></i>
                    <span class="text-[#f56e98]">*</span>
                    Valid ID
                </label>
                <input type="file" id="valid_id_path" name="valid_id_path" class="hidden">
                @if(Auth::user()->distributor->valid_id_path)
                <img class="object-cover w-32 h-32 mt-2" src="{{ asset(Auth::user()->distributor->valid_id_path ) }}" alt="Valid ID">
                @else
                <p class="mt-2 text-gray-500">No Valid ID uploaded</p>
                @endif
            </div>

            <div class="flex flex-col gap-4">
                <label for="photo_with_background_path" class="flex items-center space-x-4 font-bold cursor-pointer">
                    <i class='p-2 text-5xl text-gray-400 bg-white border-2 bx bxs-image'></i>
                    <span class="text-[#f56e98]">*</span>
                    Photo with a plain background
                </label>
                <input type="file" id="photo_with_background_path" name="photo_with_background_path" class="hidden">
                @if(Auth::user()->distributor->photo_with_background_path)
                <img class="object-cover w-32 h-32 mt-2" src="{{ asset( Auth::user()->distributor->photo_with_background_path) }}" alt="Photo with background">
                @else
                <p class="mt-2 text-gray-500">No photo with background uploaded</p>
                @endif
            </div>

            <div class="flex flex-col gap-4">
                <label for="selfie_with_id_path" class="flex items-center space-x-4 font-bold cursor-pointer">
                    <i class='p-2 text-5xl text-gray-400 bg-white border-2 bx bxs-image'></i>
                    <span class="text-[#f56e98]">*</span>
                    Selfie with ID
                </label>
                <input type="file" id="selfie_with_id_path" name="selfie_with_id_path" class="hidden">
                @if(Auth::user()->distributor->selfie_with_id_path)
                <img class="object-cover w-32 h-32 mt-2" src="{{ (Auth::user()->distributor->selfie_with_id_path) }}" alt="Selfie with ID">
                @else
                <p class="mt-2 text-gray-500">No selfie with ID uploaded</p>
                @endif
            </div>
        </div>

        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col">
                    <label for="region">Region</label>
                    <input class="w-full p-4 h-full border-[#f56e98] rounded-lg" type="text" name="region" id="region" value="{{ old('region', Auth::user()->distributor->region) }}">
                </div>
                <div class="flex flex-col">
                    <label for="province">Province</label>
                    <input class="w-full p-4 h-full border-[#f56e98] rounded-lg" type="text" name="province" id="province" value="{{ old('province', Auth::user()->distributor->province) }}">
                </div>
                <div class="flex flex-col">
                    <label for="brgy">Barangay</label>
                    <input class="w-full p-4 h-full border-[#f56e98] rounded-lg" type="text" name="brgy" id="brgy" value="{{ old('brgy', Auth::user()->distributor->brgy) }}">
                </div>
                <div class="flex flex-col">
                    <label for="city">City</label>
                    <input class="w-full p-4 h-full border-[#f56e98] rounded-lg" type="text" name="city" id="city" value="{{ old('city', Auth::user()->distributor->city) }}">
                </div>
            </div>

            <div class="flex flex-col col-span-2">
                <label for="facebook">Facebook</label>
                <input class="w-full lg:w-2/4 p-4 h-full border-[#f56e98] rounded-lg" type="url" name="facebook" id="facebook" value="{{ old('facebook', Auth::user()->socialMediaAccounts->facebook ?? '') }}">
            </div>

            <div class="flex flex-col col-span-2">
                <label for="instagram">Instagram</label>
                <input class="w-full lg:w-2/4 p-4 h-full border-[#f56e98] rounded-lg" type="url" name="instagram" id="instagram" value="{{ old('instagram', Auth::user()->socialMediaAccounts->instagram ?? '') }}">
            </div>

            <div class="flex flex-col col-span-2">
                <label for="tiktok">Tiktok</label>
                <input class="w-full lg:w-2/4 p-4 h-full border-[#f56e98] rounded-lg" type="url" name="tiktok" id="tiktok" value="{{ old('tiktok', Auth::user()->socialMediaAccounts->tiktok ?? '') }}">
            </div>
        </div>
    </div>

    <div class="flex flex-col items-center gap-2 p-8 my-20 md:flex-row">
        <div>
            <button type="submit" class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg w-60 py-2 text-white border-2 border-white">UPDATE INFO</button>
        </div>
    </div>
</form>
@endsection
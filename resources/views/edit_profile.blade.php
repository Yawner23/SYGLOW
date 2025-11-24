@extends('layouts.sidebar')

@section('title', 'Home')

@section('content')
<div class="flex flex-col justify-between p-8 text-4xl md:flex-row">
    <h1>Edit Profile</h1>
    <h1>Welcome, {{ Auth::user()->customer->first_name ?? '' }} {{ Auth::user()->customer->last_name ?? '' }}!</h1>
</div>

<form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('POST')

    <div class="flex flex-col items-center gap-8 p-8 md:flex-row">
        <img class="w-[7rem] h-[7rem] shrink-0 inline-block rounded-full object-cover"
            src="{{ asset('uploads/profile_pictures/' . (Auth::user()->customer->profile_picture ?? 'default-avatar.jpg')) }}"
            alt="avatar image">
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
        <div class="grid grid-cols-2 gap-4">
            <div class="flex flex-col">
                <label for="first_name">First Name</label>
                <input class="w-full p-4 h-full border-[#f56e98] rounded-lg" type="text" name="first_name" id="first_name" value="{{ Auth::user()->customer->first_name ?? '' }}">
            </div>
            <div class="flex flex-col">
                <label for="last_name">Last Name</label>
                <input class="w-full p-4 h-full border-[#f56e98] rounded-lg" type="text" name="last_name" id="last_name" value="{{ Auth::user()->customer->last_name ?? '' }}">
            </div>
            <div class="flex flex-col col-span-2">
                <label for="email">Email</label>
                <input class="w-full p-4 h-full border-[#f56e98] rounded-lg" type="text" name="email" id="email" value="{{ Auth::user()->email }}">
            </div>
            <div class="flex flex-col col-span-2">
                <label for="contact_number">Contact Number</label>
                <input class="w-full p-4 h-full border-[#f56e98] rounded-lg" type="text" name="contact_number" id="contact_number" value="{{ Auth::user()->customer->contact_number ?? '' }}">
            </div>
            <div class="flex flex-col col-span-2">
                <label for="password">Password</label>
                <input class="w-full p-4 h-full border-[#f56e98] rounded-lg" type="password" name="password" id="password">
            </div>
        </div>
        <div class="space-y-4">
            <div class="flex flex-col">
                <label for="month">Date of Birth</label>
                <div class="flex flex-col gap-4 lg:flex-row">
                    <select class="lg:w-60 w-full p-3 h-full border-[#f56e98] rounded-lg" name="month" id="month">
                        <option value=""> Month</option>
                        @foreach (['Jan' => 'January', 'Feb' => 'February', 'Mar' => 'March', 'Apr' => 'April', 'May' => 'May', 'Jun' => 'June', 'Jul' => 'July', 'Aug' => 'August', 'Sep' => 'September', 'Oct' => 'October', 'Nov' => 'November', 'Dec' => 'December'] as $abbr => $full)
                        <option value="{{ $abbr }}" {{ (Auth::user()->customer->date_of_birth && date('M', strtotime(Auth::user()->customer->date_of_birth)) == $abbr) ? 'selected' : '' }}>{{ $full }}</option>
                        @endforeach
                    </select>
                    <select class="lg:w-20 w-full p-3 h-full border-[#f56e98] rounded-lg" name="day" id="day">
                        <option value=""> Day</option>
                        @for ($i = 1; $i <= 31; $i++)
                            <option value="{{ $i }}" {{ (Auth::user()->customer->date_of_birth && date('j', strtotime(Auth::user()->customer->date_of_birth)) == $i) ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                    </select>
                    <select class="lg:w-40 w-full p-3 h-full border-[#f56e98] rounded-lg" name="year" id="year">
                        <option value=""> Year</option>
                        @for ($i = 1700; $i <= date('Y'); $i++)
                            <option value="{{ $i }}" {{ (Auth::user()->customer->date_of_birth && date('Y', strtotime(Auth::user()->customer->date_of_birth)) == $i) ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                    </select>
                </div>
            </div>
            <input type="text" readonly id="user_id" name="user_id" class="hidden" value="{{(Auth::user()->id)}}" />
            <div class="flex flex-col col-span-2">
                <label for="facebook">Facebook</label>
                <input class="w-full lg:w-2/4 p-4 h-full border-[#f56e98] rounded-lg" type="url" name="facebook" id="facebook" value="{{ Auth::user()->socialMediaAccounts->facebook ?? '' }}">
            </div>
            <div class="flex flex-col col-span-2">
                <label for="instagram">Instagram</label>
                <input class="w-full lg:w-2/4 p-4 h-full border-[#f56e98] rounded-lg" type="url" name="instagram" id="instagram" value="{{ Auth::user()->socialMediaAccounts->instagram ?? '' }}">
            </div>
            <div class="flex flex-col col-span-2">
                <label for="tiktok">Tiktok</label>
                <input class="w-full lg:w-2/4 p-4 h-full border-[#f56e98] rounded-lg" type="url" name="tiktok" id="tiktok" value="{{ Auth::user()->socialMediaAccounts->tiktok ?? '' }}">
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
@extends('layouts.main-app')

@section('title', 'Home')

@section('content')

@include('layouts.navbar')

<div class="relative h-[20rem]">
    <img class="absolute z-0 object-cover w-full h-full " src="{{asset('images/All-Banner.png')}}" alt="">
    <div class="container relative max-w-screen-xl py-32 mx-auto">
        <div class="flex flex-col items-center justify-center space-y-4">
            <h1 class="text-5xl font-semibold">LOGIN</h1>
            <h1 class="text-xl">Home >> <span class="text-[#f56e98]">Login</span> </h1>
        </div>
    </div>
</div>

<div class="py-20">
    @if ($errors->any())
    <div class="p-4 mb-4 text-white bg-red-500 rounded">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="container relative z-50 max-w-screen-lg mx-auto">
        <div class="relative grid grid-cols-1 -space-x-2 md:grid-cols-3">
            <div>
                <img class="object-cover hidden md:block w-full h-[40rem] rounded-l-3xl" src="{{ asset('images/Login-img.jpg') }}" alt="">
            </div>
            <div class="relative col-span-2">
                <img class="absolute z-0 w-full h-full" src="{{ asset('images/Login-Card-2.png') }}" alt="">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="relative flex flex-col items-center justify-between w-full h-full p-12 space-y-12">
                        <h1 class="text-4xl">Create Account</h1>
                        <div class="grid grid-cols-1 gap-8 md:grid-cols-1">
                            <input type="hidden" name="role" value="customer">
                            <div class="flex flex-col col-span-2 md:col-span-1">
                                <label class="font-bold" for="first_name">First Name</label>
                                <input type="text" id="first_name" name="first_name" class="border border-[#f590b0] bg-white opacity-100 w-full rounded" value="{{ old('first_name') }}" required>
                            </div>
                            <div class="flex flex-col col-span-2 md:col-span-1">
                                <label class="font-bold" for="last_name">Last Name</label>
                                <input type="text" id="last_name" name="last_name" class="border border-[#f590b0] bg-white opacity-100 w-full rounded" value="{{ old('last_name') }}" required>
                            </div>
                            <div class="flex flex-col col-span-2 md:col-span-1">
                                <label class="font-bold" for="email">Email</label>
                                <input type="email" id="email" name="email" class="border border-[#f590b0] bg-white opacity-100 w-full rounded" value="{{ old('email') }}" required>
                            </div>
                            <div class="flex flex-col col-span-2 md:col-span-1">
                                <label class="font-bold" for="contact_number">Contact Number</label>
                                <input type="tel" id="contact_number" name="contact_number" class="border border-[#f590b0] bg-white opacity-100 w-full rounded" value="{{ old('contact_number') }}" required>
                            </div>
                            <div class="flex flex-col col-span-2 md:col-span-1">
                                <label class="font-bold" for="password">Password</label>
                                <input type="password" id="password" name="password" class="border border-[#f590b0] bg-white opacity-100 w-full rounded" required>
                            </div>
                            <div class="flex flex-col col-span-2 md:col-span-1">
                                <label class="font-bold" for="password_confirmation">Confirm Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="border border-[#f590b0] bg-white opacity-100 w-full rounded" required>
                            </div>

                            {{-- ✅ Google reCAPTCHA --}}
                            <div class="mb-6">
                                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITEKEY') }}"></div>
                                @error('g-recaptcha-response')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- <div class="flex flex-col col-span-2 md:col-span-1">
                                <label class="font-bold" for="referral_code">Referral Code</label>
                                <input type="text" id="referral_code" name="referral_code" class="border border-[#f590b0] bg-white opacity-100 w-full rounded" required>
                            </div> -->
                            <div class="flex flex-col items-center col-span-2 space-y-2">
                                <button type="submit" class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg px-20 py-2 text-white border-2 border-white">SIGN UP</button>
                                <a href="{{ url('/login') }}" class="flex items-center">
                                    <p>Already Have an Account?</p><i class='text-2xl bx bx-right-arrow-alt'></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('layouts.member')
@include('layouts.footer')

{{-- ✅ Google reCAPTCHA script --}}
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection
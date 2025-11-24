@extends('layouts.main-app')

@section('title', '')

@section('content')

@include('layouts.navbar')

<div class="relative h-[20rem]">
    <img class="absolute z-0 object-cover w-full h-full" src="{{ asset('images/All-Banner.png') }}" alt="">
    <div class="container relative max-w-screen-xl py-32 mx-auto">
        <div class="flex flex-col items-center justify-center space-y-4">
            <h1 class="text-5xl font-semibold">LOGIN</h1>
            <h1 class="text-xl">Home >> <span class="text-[#f56e98]">Login</span></h1>
        </div>
    </div>
</div>

<div class="py-20">
    <div class="container relative z-50 max-w-screen-lg mx-auto">
        <div class="relative grid grid-cols-1 -space-x-1 md:grid-cols-2">
            <div>
                <img class="object-cover w-full hidden md:block h-[40rem] rounded-l-3xl" src="{{ asset('images/Login-img.jpg') }}" alt="">
            </div>
            <div class="relative">
                <img class="absolute z-0 w-full h-full" src="{{ asset('images/Login-Card-2.png') }}" alt="">
                <div class="relative flex flex-col items-center w-full h-full p-12 space-y-20">
                    <h1 class="text-4xl">Success</h1>
                    <div class="flex flex-col items-center space-y-8">
                        <div class="flex justify-center">
                            <img src="{{ asset('images/Success.png') }}" alt="">
                        </div>
                        <p class="font-semibold text-center">Congratulations, your account has been successfully created. Thank you for signing up!</p>

                        <!-- Display Temporary Email and Password -->
                        @if(session('email') && session('tempPassword'))
                        <div class="text-center">
                            <p class="text-lg font-bold">Your Temporary Login Details:</p>
                            <p><strong>Email:</strong> {{ session('email') }}</p>
                            <p><strong>Password:</strong> {{ session('tempPassword') }}</p>
                            <p class="text-sm text-gray-600">Please update your email and password after your first login.</p>
                        </div>
                        @endif

                        <a href="{{ url('login') }}">
                            <button class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg px-20 py-2 text-white border-2 border-white">CONTINUE</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.member')
@include('layouts.footer')

@endsection
@extends('layouts.main-app')

@section('title', '')

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
    <div class="container relative z-0 max-w-screen-lg mx-auto ">
        <div class="relative grid grid-cols-1 -space-x-1 md:grid-cols-2">
            <div>
                <img class="object-cover hidden md:block w-full h-[40rem] rounded-l-3xl" src="{{asset('images/Login-img.jpg')}}" alt="">
            </div>
            <div class="relative ">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <img class="absolute z-0 w-full h-full " src="{{asset('images/Login-Card-2.png')}}" alt="">
                    <div class="relative flex flex-col items-center justify-between w-full h-full p-12 space-y-12">
                        <h1 class="text-4xl">Log In to SY Glow </h1>

                        <div class="w-full space-y-4">
                            <form class="flex flex-col">
                                <label for="email" class="font-bold" :value="__('Email')">Login</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" :value="old('email')" class="w-full h-full rounded-lg border-[#f56e98]" autofocus autocomplete="username">
                                @error('email')
                                <div class="mt-2 text-sm text-red-500">{{ $message }}</div>
                                @enderror
                            </form>
                            <form class="flex flex-col">
                                <label for="password" :value="__('Password')" class="font-bold">Password</label>
                                <input type="password" name="password" id="password" required autocomplete="current-password" class="w-full h-full rounded-lg border-[#f56e98]">
                                @error('password')
                                <div class="mt-2 text-sm text-red-500">{{ $message }}</div>
                                @enderror
                            </form>
                        </div>
                        <div class="flex flex-col items-center space-y-4">
                            <button class="bg-gradient-to-r from-[#f590b0] to to-[#f56e98] rounded-lg px-20 py-2 text-white border-2 border-white">LOGIN</button>
                            <a href="{{url('/forget_password')}}">
                                <p>Forgot Username/Password?</p>
                            </a>
                        </div>
                        <a href="{{url('/create_account')}}">
                            <div class="flex items-center">
                                <p class="text-xl">Create Your Account </p>
                                <i class='text-xl bx bx-right-arrow-alt'></i>
                            </div>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@include('layouts.member')
@include('layouts.footer')
@endsection
@extends('layouts.main-app')

@section('title', 'Verify OTP')

@section('content')

@include('layouts.navbar')

<div class="relative h-[20rem]">
    <img class="absolute z-0 object-cover w-full h-full" src="{{ asset('images/All-Banner.png') }}" alt="">
    <div class="container relative max-w-screen-xl py-32 mx-auto">
        <div class="flex flex-col items-center justify-center space-y-4">
            <h1 class="text-5xl font-semibold">VERIFY OTP</h1>
            <h1 class="text-xl">Home >> <span class="text-[#f56e98]">Verify OTP</span></h1>
        </div>
    </div>
</div>

<div class="py-20">
    <div class="container relative z-50 max-w-screen-lg mx-auto">
        <div class="relative grid grid-cols-1 -space-x-1 md:grid-cols-2">
            <div>
                <img class="object-cover hidden md:block w-full h-[40rem] rounded-l-3xl" src="{{ asset('images/Login-img.jpg') }}" alt="">
            </div>
            <div class="relative">
                <img class="absolute z-0 w-full h-full" src="{{ asset('images/Login-Card-2.png') }}" alt="">
                <div class="relative flex flex-col items-center justify-between w-full h-full p-4 md:p-12">
                    <div class="space-y-14">
                        <h1 class="text-4xl text-center">OTP Verification</h1>
                        <p class="font-semibold text-center">Enter the 4 Digit Code sent to your email</p>
                        <form action="{{ route('otp.verify') }}" method="POST" class="flex flex-col space-y-4">
                            @csrf
                            <div class="grid grid-cols-4 gap-2 md:gap-8">
                                <input type="text" name="otp1" class="border-b-4 border-b-[#f590b0] border-t-transparent border-x-transparent bg-[#fff0f5] w-full h-full p-6" maxlength="1" required>
                                <input type="text" name="otp2" class="border-b-4 border-b-[#f590b0] border-t-transparent border-x-transparent bg-[#fff0f5] w-full h-full p-6" maxlength="1" required>
                                <input type="text" name="otp3" class="border-b-4 border-b-[#f590b0] border-t-transparent border-x-transparent bg-[#fff0f5] w-full h-full p-6" maxlength="1" required>
                                <input type="text" name="otp4" class="border-b-4 border-b-[#f590b0] border-t-transparent border-x-transparent bg-[#fff0f5] w-full h-full p-6" maxlength="1" required>
                            </div>
                            @error('otp')
                            <div class="text-center text-red-500">{{ $message }}</div>
                            @enderror
                            <p class="font-semibold text-center">Resend Code</p>
                            <div class="flex flex-col items-center mt-8 space-y-12">
                                <button type="submit" class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg px-20 py-2 text-white border-2 border-white">VERIFY</button>
                                <a href="{{ url('/login') }}" class="text-center">
                                    <p>Already Have an Account?</p>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.member')
@include('layouts.footer')

@endsection
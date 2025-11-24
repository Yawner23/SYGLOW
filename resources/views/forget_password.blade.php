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
<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div class="py-20">
        <div class="container relative z-50 max-w-screen-lg mx-auto ">
            <div class="relative grid grid-cols-1 -space-x-1 md:grid-cols-2">
                <div>
                    <img class="object-cover hidden md:block w-full h-[40rem] rounded-l-3xl" src="{{asset('images/Login-img.jpg')}}" alt="">
                </div>
                <div class="relative ">
                    <img class="absolute z-0 w-full h-full " src="{{asset('images/Login-Card-2.png')}}" alt="">
                    <div class="relative flex flex-col items-center w-full h-full p-12 space-y-20 ">
                        <h1 class="text-4xl">Forgot Password</h1>
                        <div class="w-full space-y-4">
                            <label for="email" class="font-bold">Email</label>
                            <input type="email" id="email" name="email" class="w-full rounded-lg border-[#f56e98]" required>
                            <p class="font-semibold text-center">Please enter 4 digit code sent to <br> sample@email.com</p>
                        </div>
                        <button type="submit" class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg px-20 py-2 text-white border-2 border-white mt-4">SEND</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@include('layouts.member')
@include('layouts.footer')
@endsection
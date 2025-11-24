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
<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <div class="py-20">
        <div class="container relative z-50 max-w-screen-lg mx-auto ">
            <div class="relative grid grid-cols-1 -space-x-1 md:grid-cols-2">
                <div>
                    <img class="object-cover hidden md:block w-full h-[40rem] rounded-l-3xl" src="{{asset('images/Login-img.jpg')}}" alt="">
                </div>
                <div class="relative ">
                    <img class="absolute z-0 w-full h-full " src="{{asset('images/Login-Card-2.png')}}" alt="">
                    <div class="relative flex flex-col w-full h-full p-12 space-y-12 ">
                        <div>
                            <label for="password" class="font-bold">New Password</label>
                            <input type="password" id="password" name="password" class="w-full rounded-lg border-[#f56e98]" required>
                        </div>
                        <div>
                            <label for="password_confirmation" class="font-bold">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="w-full rounded-lg border-[#f56e98]" required>
                        </div>
                        <button type="submit" class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg px-20 py-2 text-white border-2 border-white mt-4">Reset Password</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@include('layouts.member')
@include('layouts.footer')
@endsection
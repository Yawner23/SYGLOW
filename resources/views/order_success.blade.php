@extends('layouts.main-app')

@section('title', 'Order Success')

@section('content')

@include('layouts.navbar')

<div class="relative h-[20rem]">
    <img class="absolute z-0 object-cover w-full h-full" src="{{ asset('images/All-Banner.png') }}" alt="">
    <div class="container relative max-w-screen-xl py-32 mx-auto">
        <div class="flex flex-col items-center justify-center space-y-4">
            <h1 class="text-5xl font-semibold">ORDER SUCCESS</h1>
            <h1 class="text-xl">Home >> <span class="text-[#f56e98]">Order Success</span></h1>
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
                    <h1 class="text-4xl">Success!</h1>
                    <div class="flex flex-col items-center space-y-8">
                        <div class="flex justify-center">
                            <img src="{{ asset('images/Success.png') }}" alt="Success">
                        </div>
                        <p class="font-semibold text-center">Congratulations, your order has been successfully placed. Thank you for shopping with us!</p>

                        <!-- Optional Order Details -->
                        @if(session('order_id'))
                        <div class="text-center">
                            <p class="text-lg font-bold">Order ID: {{ session('order_id') }}</p>
                            <p class="text-sm text-gray-600">You can track your order in your profile.</p>
                        </div>
                        @endif

                        <a href="{{ url('/') }}">
                            <button class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg px-20 py-2 text-white border-2 border-white">CONTINUE SHOPPING</button>
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
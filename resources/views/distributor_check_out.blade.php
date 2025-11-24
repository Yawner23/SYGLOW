@extends('layouts.sidebar')

@section('title', 'Home')

@section('content')
<div class="flex flex-col justify-between p-8 text-4xl md:flex-row">
    <h1>Check Out</h1>
    <h1>Welcome, {{ Auth::user()->name }}!</h1>
</div>
<div class="flex flex-col justify-between p-8 lg:flex-row">
    <div class="flex items-center space-x-8">
        <img class="rounded-full h-[6rem] object-cover w-[6rem]" src="{{ asset('uploads/profile_pictures/' . (Auth::user()->distributor->profile_picture ?? 'default-avatar.jpg')) }}" alt="">
        <div>
            <h1 class="text-[#f590b0] text-4xl">{{ Auth::user()->name }}</h1>
            <p class="text-xl">{{ Auth::user()->id }}</p>
            <p class="text-xl">{{ Auth::user()->email }}</p>
        </div>
    </div>
    <div class="flex flex-col items-center lg:flex-row">
        <a href="{{url('/distributor_id')}}">
            <div>
                <button class="bg-gradient-to-r from-[#f590b0] to to-[#f56e98] rounded-xl w-60 py-2 text-white border-2 border-white"> VIEW ID</button>
            </div>
        </a>
        <a href="{{url('/distributor_list')}}">
            <div>
                <button class="bg-gradient-to-r from-[#f590b0] to to-[#f56e98] rounded-xl w-60 py-2 text-white border-2 border-white"> VIEW DISTRIBUTORS</button>
            </div>
        </a>
    </div>
</div>

<div class="w-full h-screen py-20 ">
    <div class="container max-w-screen-md mx-auto bg-white shadow-lg ">
        <div class="flex flex-col items-center justify-center">
            <div class="p-8 space-y-12 md:p-20">
                <div class="flex flex-row justify-center space-x-4">
                    <div class="">
                        <img class="object-contain w-full h-full -mt-4 " src="{{asset('images/Success.png')}}" alt="">
                        <h1 class="font-semibold">Thank You for Your Purchase!</h1>
                    </div>

                </div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <p>Subtotal</p>
                        <p>₱{{ number_format(session('subtotal'), 2) }}</p>
                    </div>
                    <div class="flex justify-between">
                        <p>Shipping</p>
                        <p>Enter Shipping Address</p>
                    </div>
                    <div class="flex justify-between">
                        <p>Total</p>
                        <p>₱{{ number_format(session('total'), 2) }}</p>
                    </div>
                </div>

                <div class="flex flex-col items-center justify-center space-y-4">
                    <p class="text-center text-md">Your order has been successfully placed and your payment <br> has been processed.</p>
                    <p class="text-center text-md">Your Order Number is {{ session('payment_id') }}. and <br> Payment Method is {{ session('payment_method') }}.</p>
                    <a href="{{url('/distributor_ordered_items' , Auth::user()->id)}}">
                        <button class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg md:px-40 py-2 text-white border-2 border-white w-full">
                            BACK TO HOME
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
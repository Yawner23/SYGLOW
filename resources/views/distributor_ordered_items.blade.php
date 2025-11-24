@extends('layouts.sidebar')

@section('title', 'Home')

@section('content')
<div class="flex flex-col justify-between p-8 text-4xl md:flex-row">
    <h1>Ordered Items</h1>
    <h1>Welcome, {{ Auth::user()->distributor->name }}!</h1>
</div>
<div class="flex flex-col justify-between p-8 lg:flex-row ">
    <div class="flex items-center space-x-8">
        <img class="rounded-full h-[6rem] object-cover w-[6rem]" src="{{ asset('uploads/profile_pictures/' . (Auth::user()->distributor->profile_picture ?? 'default-avatar.jpg')) }}" alt="">
        <div>
            <h1 class="text-[#f590b0] text-4xl">{{ Auth::user()->distributor->name }}</h1>
            <p class="text-xl">{{ Auth::user()->id }}</p>
            <p class="text-xl">{{ Auth::user()->email }}</p>
        </div>
    </div>
    <a href="{{url('/distributor_list')}}">
        <button class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg px-12 py-2 text-white border-2 border-white">VIEW DISTRIBUTORS</button>
    </a>
</div>

<div class="container max-w-screen-lg p-8">
    <div class="grid grid-cols-1 gap-12 lg:grid-cols-2">

        @foreach ($payments as $payment)
        <a href="{{url('/distributor_payment_summary', $payment->id)}}">
            <div class="p-8 border border-[#f56e98] rounded hover:shadow hover:shadow-[#f56e98]">
                <ul class="grid grid-cols-2 gap-4">
                    <li>Bundle:</li>
                    <li>#{{$payment->id}}</li>
                    @foreach ($payment->products as $product)
                    <li>Items:</li>
                    <li>{{ $product->product->product_name }} {{ $product->quantity }}x</li>
                    @endforeach
                    <li>Total Amount:</li>
                    <li>₱{{ $payment->total }}</li>
                    <li>Status:</li>
                    <li>{{ $payment->status }}</li>
                </ul>
                <div class="flex justify-end">
                    <p class="underline">view details</p>
                </div>
            </div>
        </a>
        @endforeach

    </div>

</div>

@endsection
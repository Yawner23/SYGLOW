@extends('layouts.main-app')

@section('title', '')

@section('content')
<div class="bg-[#fdebdd]">
    <div class="container flex justify-center px-4 py-2 mx-auto space-y-2 text-sm md:flex-row md:space-y-0">
        <img src="{{asset('images/logo-header.png')}}" alt="">
    </div>
</div>
<div class="relative w-full h-full">
    <img class="absolute z-0 object-cover w-full h-full" src="{{asset('images/Golden-Sparkling-Wave.png')}}" alt="">

    @if(isset($clearLocalStorage) && $clearLocalStorage)
    <input type="hidden" id="clear-local-storage" value="true">
    @endif
    <div class="bg-[#fffcf7] h-screen w-full py-20 ">
        <div class="container relative max-w-screen-sm mx-auto bg-white shadow-lg ">
            <div class="flex flex-col items-center justify-center">
                <div class="p-8 space-y-12 md:p-20">
                    <div class="flex flex-row justify-center space-x-4">
                        <div class="">
                            <img class="object-contain w-full h-full -mt-4 " src="{{asset('images/Success.png')}}" alt="">
                            <h1 class="font-semibold">Thank You for Your Purchase!</h1>
                        </div>

                    </div>
                    <div class="space-y-2">
                        @foreach($subtotals as $subtotal)
                        <div class="flex justify-between">
                            <p>Subtotal</p>
                            <p>₱{{$subtotal }}</p>
                        </div>
                        @endforeach

                        <div class="flex justify-between">
                            <p>Total</p>
                            <p> ₱{{ $amountPaid }}</p>
                        </div>
                    </div>

                    <div class="flex flex-col items-center justify-center space-y-4">
                        <p class="text-center text-md">Your order has been successfully placed and your payment <br> has been processed.</p>
                        <p class="text-center text-md">Your Order Number is {{ $payment_id }}. <br> Transaction ID: {{ $transaction_id }}.<br> Payment Method is {{ $payment_method }}.</p>
                        <a href="{{url('/')}}">
                            <button class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg md:px-40 py-2 text-white border-2 border-white w-full">
                                BACK TO HOME
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // This script will run only if the flag is set
        const clearFlag = document.getElementById('clear-local-storage');
        if (clearFlag && clearFlag.value === 'true') {
            localStorage.clear();
        }
    </script>
</div>

@endsection
@extends('layouts.main-app')

@section('title', 'Wishlist')

@section('content')

@include('layouts.navbar')
<div class="relative h-[20rem]">
    <img class="absolute object-cover w-full h-full " src="{{asset('images/All-Banner.png')}}" alt="">
    <div class="container relative max-w-screen-xl py-32 mx-auto">
        <div class="flex flex-col items-center justify-center space-y-4">
            <h1 class="text-5xl font-semibold">Be Our Member</h1>
            <h1 class="text-xl">Home >> <span class="text-[#f56e98]">Be Our Member</span> >> <span class="text-[#f56e98]">Sign up</span></h1>
        </div>
    </div>
</div>


<div class="relative w-full h-full">
    <img class="absolute object-cover w-full h-full" src="{{asset('images/Golden-Sparkling-Wave.png')}}" alt="">
    <div class="container relative max-w-screen-xl py-20 mx-auto">
        <div class="flex flex-col items-center justify-center space-y-4">
            <h1 class="text-5xl font-bold ">Wishlist</h1>
            <p>3 items</p>
        </div>
        <div class="flex justify-center w-full py-4">
            <img src="{{asset('images/wishlist-line.png')}}" alt="">
        </div>
        <div class="grid grid-cols-1 gap-8 px-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($wishlist as $wishlists)
            @if($wishlists->product->images->isNotEmpty())
            @php
            $firstImage = $wishlists->product->images->first();
            @endphp

            <div class="relative flex flex-col group">
                <div class="absolute flex items-center justify-center w-full h-full px-2 mt-20">
                    <div class="w-full">
                        <button id="open-modal"
                            data-product-id="{{$wishlists->product->id}}"
                            data-product-name=" {{$wishlists->product->product_name}}"
                            data-product-price="{{ $wishlists->product->product_price }}"
                            data-product-image="{{ asset('images/uploads/product_images/' . $firstImage->image_path) }}"
                            class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-xl w-full py-2 text-white transition-opacity duration-300 opacity-0  group-hover:opacity-100"> ADD TO CART </button>
                    </div>
                </div>
                <img class="bg-[#fdebdd] object-contain p-20 h-[25rem] w-full" src="{{ asset('images/uploads/product_images/' . $firstImage->image_path) }}" alt="">
                <div class="my-8 space-y-2">
                    <div class="flex justify-between ">
                        <h1 class="text-sm">{{$wishlists->product->product_name}}</h1>
                        <form action="{{ route('wishlist.destroy', $wishlists->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="relative text-sm text-red-600 bg-transparent border-0 cursor-pointer hover:underline">
                                <i class='bx bx-x-circle'></i> Remove
                            </button>
                        </form>

                    </div>
                    <p class="text-[#f590b0]">₱{{ $wishlists->product->product_price }}.00</p>
                    <div class="flex items-center text-[#fdd900]">
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>

</div>


@include('layouts.footer')
@endsection
@extends('layouts.sidebar')

@section('title', 'Home')

@section('content')
<div class="flex flex-col justify-between p-8 text-4xl md:flex-row">
    <h1>Purchase</h1>
    <h1 class="text-4xl font-semibold">
        Welcome,
        @if(Auth::user()->distributor)
        {{ Auth::user()->distributor->name }}!
        @else
        {{ Auth::user()->name }}!
        @endif
    </h1>
</div>
<div class="flex flex-col justify-between p-8 lg:flex-row">
    <div class="flex items-center space-x-8">
        <img class="rounded-full h-[6rem] object-cover w-[6rem]" src="{{ asset('uploads/profile_pictures/' . (Auth::user()->distributor->profile_picture ?? 'default-avatar.jpg')) }}" alt="">
        <div>
            <h1 class="text-[#f590b0] text-4xl">
                {{ optional(Auth::user()->distributor)->name ?? Auth::user()->name }}
            </h1>

            <p class="text-xl"> {{ Auth::user()->id }}</p>
            <p class="text-xl"> {{ Auth::user()->email }}</p>
        </div>
    </div>
    <div>
        <a href="{{url('/distributor_list')}}">
            <button class="bg-gradient-to-r from-[#f590b0] to to-[#f56e98] rounded-xl w-60 py-2 text-white border-2 border-white"> VIEW DISTRIBUTORS</button>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 p-8 mt-40 md:gap-4 lg:grid-cols-4">
    <div class="col-span-3">
        <div class="flex flex-col justify-end mb-8 space-y-4 lg:flex-row xl:space-y-0">

            <form method="GET" action="{{ url()->current() }}" class="flex flex-col items-center space-x-2 space-y-2 md:space-y-0 md:flex-row">
                <input class="rounded-2xl border-[#f590b0] shadow shadow-[#f590b0]" type="search" name="search" value="{{ request('search') }}">
                <button type="submit" class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-xl w-full py-2 px-8 text-white border-2 border-white">SEARCH</button>
            </form>
        </div>
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-2 xl:grid-cols-3">
            @foreach ($products as $product)
            @if($product->images->isNotEmpty())
            @php
            $firstImage = $product->images->first();
            @endphp

            <div class="relative flex flex-col group">
                <div class="absolute right-4 top-2">
                    <div class="space-y-2 ">
                        <div>
                            <a class="transition-opacity duration-300 opacity-0 group-hover:opacity-100" href="{{url('products_details/' . $product->id)}}">
                                <img src="{{ asset('images/prod-view.png') }}" alt="">
                            </a>
                        </div>
                    </div>
                </div>

                <div class="absolute flex items-center justify-center w-full h-full px-2 mt-14">
                    <div class="w-full">
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <input type="number" id="product_price" name="product_price" readonly value="{{$product->product_price}}" hidden>
                            <button type="submit"
                                class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-xl w-full py-2 text-white transition-opacity duration-300 opacity-0 group-hover:opacity-100">
                                ADD TO CART
                            </button>
                        </form>
                    </div>
                </div>

                <img class="bg-[#fdebdd] object-contain p-8 h-[20rem] w-full" src="{{ asset('images/uploads/product_images/' . $firstImage->image_path) }}" alt="">
                @endif
                <div class="my-8 space-y-2">
                    <h1 class="text-sm">{{ optional($product->product)->name ?? $product->product_name ?? 'N/A' }}</h1>

                    <p class="text-[#f590b0]">₱{{$product->product_price}}</p>
                    <div class="flex items-center text-[#fdd900]">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class='bx bxs-star {{ $i <= round($product->averageRating) ? "text-[#fdd900]" : "text-gray-300" }}'></i>
                            @endfor
                            <span class="ml-2 text-gray-600">({{ number_format($product->averageRating, 1) }})</span>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
    <div>
        <form id="filterForm" method="GET" action="{{ url()->current() }}">
            <section>
                <div class="bg-[#f56e98] text-white p-2">
                    Category
                </div>
                <hr class="my-4">
                @foreach ($category as $categories)
                <div class="flex items-center justify-between">
                    <label for="category_{{ $categories->id }}">{{ $categories->category_name }}</label>
                    <input type="checkbox" value="{{ $categories->id }}" id="category_{{ $categories->id }}" name="category[]"
                        {{ in_array($categories->id, (array)request('category')) ? 'checked' : '' }}>
                </div>
                @endforeach
            </section>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('filterForm');
                const checkboxes = form.querySelectorAll('input[type="checkbox"]');

                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        form.submit();
                    });
                });
            });
        </script>
        <section>
            <div class="bg-[#f56e98] text-white p-2">
                Availability
            </div>
            <hr class="my-4">
            <div class="flex items-center justify-between">
                <label for="stock">In Stock ({{$in_stock}})</label>
            </div>

            <div class="flex items-center justify-between">
                <label for="out_of_stock">Out of Stock ({{$out_of_stock}})</label>
            </div>
        </section>
    </div>
</div>
@endsection
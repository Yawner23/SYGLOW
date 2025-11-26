@extends('layouts.main-app')

@section('title', 'Products')

@section('content')

@include('layouts.navbar')
<div class="relative h-[20rem]">
    <img class="absolute z-0 object-cover w-full h-full " src="{{asset('images/All-Banner.png')}}" alt="">
    <div class="container relative max-w-screen-xl py-32 mx-auto">
        <div class="flex flex-col items-center justify-center space-y-4">
            <h1 class="text-5xl font-semibold">Products</h1>
            <h1 class="text-xl">Home >> <span class="text-[#f56e98]">Collections</span> </h1>
        </div>
    </div>
</div>
<div class="relative w-full h-full">
    <img class="absolute object-cover w-full h-full" src="{{asset('images/Golden-Sparkling-Wave.png')}}" alt="">
    <div class="flex justify-center">
        <img class="absolute object-cover w-full md:w-[40rem] h-[15rem]" src="{{asset('images/Products.png')}}" alt="">
    </div>
    <div class="container relative max-w-screen-xl mx-auto mt-32 overflow-hidden">
        <div class="grid grid-cols-1 px-4 gap-y-12 lg:gap-y-0 md:gap-4 md:grid-cols-4">
            <div class="" data-aos="fade-right" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
                <form id="filter-form" method="GET" action="{{ url('/products') }}">
                    <section>
                        <div class="bg-[#f56e98] text-white p-2">Category</div>
                        <img class="my-4" src="{{ asset('images/wishlist-line.png') }}" alt="">
                        @foreach ($category as $categories)
                        <div class="flex items-center justify-between">
                            <label for="category_{{ $categories->id }}">{{ $categories->category_name }}</label>
                            <input type="checkbox" value="{{ $categories->id }}" id="category_{{ $categories->id }}" name="category[]" class="filter-checkbox" {{ request()->has('category') && in_array($categories->id, request()->category) ? 'checked' : '' }}>
                        </div>
                        @endforeach
                    </section>
                    <section>
                        <div class="bg-[#f56e98] text-white p-2">
                            Availability
                        </div>
                        <img class="my-4" src="{{ asset('images/wishlist-line.png') }}" alt="">
                        <div class="flex items-center justify-between">
                            <label for="stock">In Stock ({{ $in_stock }})</label>
                        </div>

                        <div class="flex items-center justify-between">
                            <label for="out_of_stock">Out of Stock ({{ $out_of_stock }})</label>
                        </div>
                    </section>
                    <section>
                        <div class="bg-[#f56e98] text-white p-2">Product Type</div>
                        <img class="my-4" src="{{ asset('images/wishlist-line.png') }}" alt="">
                        @foreach ($product_types as $product_type)
                        <div class="flex items-center justify-between">
                            <label for="product_type_{{ $product_type->id }}">{{ $product_type->product_type }}</label>
                            <input type="checkbox" value="{{ $product_type->id }}" id="product_type_{{ $product_type->id }}" name="product_type[]" class="filter-checkbox" {{ request()->has('product_type') && in_array($product_type->id, request()->product_type) ? 'checked' : '' }}>
                        </div>
                        @endforeach
                    </section>
                </form>


                <section>
                    <div class="bg-[#f56e98] text-white p-2">
                        Hot Deals
                    </div>
                    <img class="my-4" src="{{asset('images/wishlist-line.png')}}" alt="">
                </section>
                <section>
                    <div class="relative w-full h-full">
                        <img class="object-cover w-full h-full " src="{{asset('images/Product-ads-model.png')}}" alt="">
                        <div class="absolute bottom-0 bg-gradient-to-t from-[#f590b0] to-transparent h-full w-full opacity-80">
                        </div>
                        <div class="absolute inset-0 flex flex-col items-center justify-center h-full">
                            <h1 class="opacity-100">TARGETED TREATMENTS</h1>
                            <h1 class="text-6xl text-white opacity-100">Serums</h1>
                            <div class="absolute opacity-100 bottom-8">
                                <h1 class="text-4xl">50% SALE</h1>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="col-span-3" data-aos="fade-left" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
                <div class="flex flex-col justify-between gap-4 md:flex-row">
                    <ul class="flex items-center gap-4">
                        <li>
                            <label for="sort">Sort By:</label>
                        </li>
                        <li>
                            <select class="w-40 p-4 rounded-lg h-14" name="sort" id="sort" onchange="submitFilterForm()">
                                <option value="" {{ request('sort') == '' ? 'selected' : '' }}>Default</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                <!-- Add more sorting options as needed -->
                            </select>
                        </li>
                    </ul>
                </div>
                <img class="my-4" src="{{asset('images/wishlist-line.png')}}" alt="">

                <div class="relative grid grid-cols-1 gap-6 md:grid-cols-3 lg:grid-cols-3">
                    @foreach ($products->where('quantity', '>', 0) as $product)
                    @php
                    $hasImages = $product->images->isNotEmpty();
                    $firstImage = $hasImages ? $product->images->first() : null;
                    @endphp

                    @if($hasImages)
                    <div class="relative flex flex-col group" data-aos="zoom-in" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
                        <!-- Product Image with Overlaid Actions -->
                        <div class="relative w-full h-full">
                            <a href="{{ url('products_details/' . $product->id) }}">
                                <img class="object-cover w-full h-full" src="{{ asset('images/uploads/product_images/' . $firstImage->image_path) }}" alt="{{ $product->product_name }}">
                            </a>

                            <!-- Action Icons -->
                            <div class="absolute space-y-8 right-4 top-2">
                                <!-- Product Details Link -->
                                <div class="relative z-10 transition-opacity duration-300 opacity-0 group icon-container group-hover:opacity-100">
                                    <a href="{{ url('products_details/' . $product->id) }}">
                                        <img src="{{ asset('images/prod-view.png') }}" alt="View Product">
                                        <span class="absolute top-0 px-2 py-1 text-xs text-gray-500 transform -translate-x-1/2 -translate-y-full bg-gray-200 rounded icon-text left-1/2">
                                            View Product
                                        </span>
                                    </a>
                                </div>

                                <!-- Wishlist Button -->
                                <div class="relative z-10 transition-opacity duration-300 opacity-0 group icon-container group-hover:opacity-100">
                                    @if (Auth::check())
                                    <form action="{{ route('wishlist.store', ['product_id' => $product->id, 'customer_id' => Auth::user()->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit">
                                            <img src="{{ asset('images/prod-heart.png') }}" alt="Add to Wishlist">
                                            <span class="absolute top-0 px-2 py-1 text-xs text-gray-500 transform -translate-x-1/2 -translate-y-full bg-gray-200 rounded icon-text left-1/2">
                                                Add Wishlist
                                            </span>
                                        </button>
                                    </form>
                                    @else
                                    <a href="{{ url('wishlist') }}">
                                        <button type="button">
                                            <img src="{{ asset('images/prod-heart.png') }}" alt="Add to Wishlist">
                                            <span class="absolute top-0 px-2 py-1 text-xs text-gray-500 transform -translate-x-1/2 -translate-y-full bg-gray-200 rounded icon-text left-1/2">
                                                Add Wishlist
                                            </span>
                                        </button>
                                    </a>
                                    @endif
                                </div>
                            </div>

                            <!-- Add to Cart Button -->
                            <div class="absolute flex items-center justify-center w-full px-2 -mt-14">
                                @if($product->availability !== 'out_of_stock')
                                <button
                                    id="open-modal"
                                    data-product-id="{{ $product->id }}"
                                    data-product-name="{{ $product->product_name }}"
                                    data-product-discount_price="{{ $product->discount_price }}"
                                    data-product-price="{{ $product->product_price }}"
                                    data-product-image="{{ asset('images/uploads/product_images/' . $firstImage->image_path) }}"
                                    class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-xl w-full py-2 text-white transition-opacity duration-300 opacity-0 group-hover:opacity-100">
                                    ADD TO CART
                                </button>
                                @endif
                            </div>

                        </div>

                        <!-- Product Info -->
                        <div class="my-8 space-y-2">
                            <h1 class="text-sm">{{ $product->product_name }}</h1>
                            @if($product->discount_price > 0)
                            <p>
                                <span class="line-through text-gray-400">₱{{ number_format($product->product_price, 2) }}</span>
                                <span class="text-[#f590b0] font-bold">₱{{ number_format($product->discount_price, 2) }}</span>
                            </p>
                            @else
                            <p class="text-[#f590b0] font-bold">₱{{ number_format($product->product_price, 2) }}</p>
                            @endif

                            <p class="text-[#f590b0]">Quantity: {{ $product->quantity }}</p>
                            <div class="flex items-center text-[#fdd900]">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class='bx bxs-star {{ $i <= round($product->averageRating) ? "text-[#fdd900]" : "text-gray-300" }}'></i>
                                    @endfor
                                    <span class="ml-2 text-gray-600">({{ number_format($product->averageRating, 1) }})</span>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach

                    <script>
                        document.querySelectorAll('.icon-container').forEach(container => {
                            container.addEventListener('mouseover', () => {
                                container.querySelector('.icon-text').style.opacity = '1';
                            });
                            container.addEventListener('mouseout', () => {
                                container.querySelector('.icon-text').style.opacity = '0';
                            });
                        });
                    </script>

                    <style>
                        .icon-text {
                            opacity: 0;
                            transition: opacity 0.3s ease;
                        }
                    </style>

                </div>
                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            document.getElementById('filter-form').submit();
        });
    });

    function submitFilterForm() {
        document.getElementById('filter-form').submit();
    }
</script>

@include('layouts.member')
@include('layouts.footer')
@endsection
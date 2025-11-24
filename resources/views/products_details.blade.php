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
    <img class="absolute z-0 object-cover w-full h-full" src="{{asset('images/Golden-Sparkling-Wave.png')}}" alt="">

    <div class="container relative z-10 max-w-screen-xl mx-auto my-20">
        <div class="grid grid-cols-1 gap-12 overflow-hidden md:grid-cols-2">
            <div data-aos="fade-right" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
                @if($product->images->isNotEmpty())
                @php
                $firstImage = $product->images->first();
                @endphp
                <img id="main-image" class=" object-contain  py-0 h-[40rem] w-full " src="{{ asset('images/uploads/product_images/' . $firstImage->image_path) }}" alt="">
                @endif
                <div class="relative mt-4 swiper multiple-slide-carousel swiper-container">
                    <div class="swiper-wrapper">
                        @foreach($product->images as $image)
                        <div class="swiper-slide">
                            <img class=" object-contain  h-[12rem] w-full thumbnail"
                                data-image="{{ asset('images/uploads/product_images/' . $image->image_path) }}"
                                src="{{ asset('images/uploads/product_images/' . $image->image_path) }}"
                                alt="">
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <p>
                            {!!$product->product_description!!}
                        </p>
                    </div>
                    <div class="grid grid-cols-2">
                        <h1>Skin Type:</h1>
                        <p>
                            @foreach($product->skinTypes as $skinType)
                            {{ $skinType->skin_type }}@if(!$loop->last), @endif
                            @endforeach
                        </p>
                    </div>
                    <div class="grid grid-cols-2">
                        <h1>Benefits:</h1>
                        <p>
                            @foreach($product->benefits as $benefit)
                            {{ $benefit->benefit_name }}@if(!$loop->last), @endif
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-8" data-aos="fade-left" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
                <h1 class="text-4xl font-bold">{{$product->product_name}}</h1>


                <img class="my-4" src="{{asset('images/wishlist-line.png')}}" alt="">
                <div class="grid grid-cols-2">
                    <h1>Price:</h1>
                    <p>
                        @if($product->prices->isNotEmpty())
                        @foreach($product->prices as $price)
                        @if($price->discount_price && $price->discount_price > 0)
                        <span class="line-through text-gray-400">₱{{ number_format($price->price, 2) }}</span>
                        <span class="">₱{{ number_format($price->discount_price, 2) }}</span>
                        @else
                        <span class="">₱{{ number_format($price->price, 2) }}</span>
                        @endif
                        @endforeach
                        @else
                        No price available for this consumer.
                        @endif
                    </p>

                </div>
                <div class="grid grid-cols-2">
                    <h1>Shelf Life:</h1>
                    <p>{{$product->shelf_life}}</p>
                </div>
                <div class="grid grid-cols-2">
                    <h1>Volume:</h1>
                    <p>{{$product->volume}} ml</p>
                </div>
                <div class="grid grid-cols-2">
                    <h1>Product Edition:</h1>
                    <p>{{$product->edition}}</p>
                </div>
                <div class="grid grid-cols-2">
                    <h1>Product Form:</h1>
                    <p>{{$product->product_form}}</p>
                </div>
                <div class="grid grid-cols-2">
                    <h1>Quantity Per Pack:</h1>
                    <p>{{$product->packTypes->quantity_per_pack}}</p>
                </div>
                <div class="grid grid-cols-2">
                    <h1>Quantity:</h1>
                    <p>{{$product->quantity}}</p>
                </div>

                <div class="grid grid-cols-2">
                    <h1>Pack Type:</h1>
                    <p>{{$product->packTypes->pack_type}}</p>
                </div>

                <div class="grid grid-cols-2">
                    <h1>Weight:</h1>
                    <div class="flex items-center space-x-2">
                        @foreach($product->weights as $weight)
                        <p class="px-2 border rounded-lg hover:border-[#f56e98] hover:text-[#f56e98]">{{$weight->weights}} {{$weight->weight_unit}}</p>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-2">
                    <h1>Product Dimensions:</h1>
                    <div class="flex items-center space-x-4">
                        <div class="flex flex-col items-center">
                            <h1>Length</h1>
                            <p class="px-2 border rounded-lg hover:border-[#f56e98] hover:text-[#f56e98]">{{$product->dimensions->length_cm}} cm</p>
                        </div>
                        <div class="flex flex-col items-center">
                            <h1>Width</h1>
                            <p class="px-2 border rounded-lg hover:border-[#f56e98] hover:text-[#f56e98]">{{$product->dimensions->width_cm}} cm</p>
                        </div>
                        <div class="flex flex-col items-center">
                            <h1>Height</h1>
                            <p class="px-2 border rounded-lg hover:border-[#f56e98] hover:text-[#f56e98]">{{$product->dimensions->height_cm}} cm</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2">
                    <h1>Category:</h1>
                    <p>{{$product->productType->category->category_name}}</p>
                </div>

                <div class="grid grid-cols-2">
                    <h1>Availability:</h1>
                    <p class="{{ $product->availability == 'in_stock' ? 'text-green-400' : 'text-red-400' }}">
                        {{ $product->availability == 'in_stock' ? 'In Stock!' : 'Out of Stock!' }}
                    </p>
                </div>

                <div>
                    <label for="quantity-input" class="block mb-2 ">Quantity:</label>
                    <div class="relative flex items-center max-w-[8rem]">
                        <button type="button" id="decrement-button" data-input-counter-decrement="quantity-input" class="p-3 bg-white border border-gray-300 hover:bg-[#f56e98] rounded-s-lg h-11  text-[#f56e98] hover:text-white ">
                            <svg type="button" id="decrement-button" data-input-counter-decrement="quantity-input" class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                            </svg>
                        </button>
                        <input type="text" id="quantity-input" data-input-counter aria-describedby="helper-text-explanation" class="bg-white border-x-0 border-gray-300 h-11 text-center text-[#f56e98] text-sm  block w-full py-2.5 placeholder-[#f56e98]" placeholder="1" required />
                        <button type="button" id="increment-button" data-input-counter-increment="quantity-input" class="p-3 bg-white border border-gray-300 hover:bg-[#f56e98] rounded-e-lg h-11  text-[#f56e98] hover:text-white ">
                            <svg type="button" id="increment-button" data-input-counter-increment="quantity-input" class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex flex-col space-y-2">
                    <div>
                        <div class="w-full">
                            @if($product->availability !== 'out_of_stock')

                            @foreach($product->prices as $price)
                            <button id="open-modal"
                                data-product-id="{{$product->id}}"
                                data-product-name="{{$product->product_name}}"
                                data-product-price="{{$price->price }}"
                                data-product-discount_price="{{$price->discount_price }}"
                                data-product-image="{{ asset('images/uploads/product_images/' . $firstImage->image_path) }}"
                                data-quantity-input="#quantity-input"
                                class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-xl px-12 py-2 text-white border-2 border-white flex items-center justify-between">
                                ADD TO CART <img src="{{ asset('images/prod-shopping-cart.png') }}" alt="">
                            </button>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    <div>

                        @if (Auth::check() && Auth::user()->id)
                        <form action="{{ route('wishlist.store', ['product_id' => $product->id, 'customer_id' => Auth::user()->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <button type="submit" class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-xl px-12 py-2 text-white border-2 border-white flex items-center justify-between">ADD TO WISHLIST
                                <img src="{{asset('images/prod-heart.png')}}" alt="">
                            </button>
                        </form>
                        @else
                        <a href="{{url('wishlist')}}">
                            <button type="submit" class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-xl px-12 py-2 text-white border-2 border-white flex items-center justify-between">ADD TO WISHLIST
                                <img src="{{asset('images/prod-heart.png')}}" alt="">
                            </button>
                        </a>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2 ">
                        <a href="https://www.facebook.com/dialog/share?app_id=YOUR_APP_ID&display=popup&href={{ urlencode(url()->current()) }}&redirect_uri={{ urlencode(url()->current()) }}"
                            target="_blank"
                            class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-xl px-12 py-2 text-white border-2 border-white flex items-center justify-between">
                            SHARE TO FACEBOOK
                            <i class='text-xl bx bx-share-alt'></i>
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach($reviewComments as $comment)
    @if($comment)

    <div class="container relative z-10 max-w-screen-xl mx-auto " data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
        <div class="flex ">
            <div>
                <button class="h-full px-12 py-2 text-white bg-black border-2 border-white rounded-t-lg">Reviews </button>
            </div>
        </div>
        <div class="p-12 bg-white border-2">
            <div class="flex flex-row items-center space-x-4">
                <div>
                    @if($comment->profile_picture)
                    <img src="{{ asset('uploads/profile_pictures/' . $comment->profile_picture) ?? 'default-avatar.jpg' }}" alt="Review Image" width="100">
                    @endif
                </div>

                <div>
                    <h1 class="text-xl font-bold"> {{ $comment->first_name }} {{ $comment->last_name }}</h1>
                    <p>
                        {{ $comment->comment }}
                    </p>
                    @if($comment->image)
                    <img src="{{ asset('images/uploads/' . $comment->image) }}" alt="Review Image" width="100">
                    @endif
                </div>
            </div>


        </div>
    </div>
    @endif
    @endforeach


    <div class="container relative max-w-screen-xl mx-auto my-12" data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
        <h1 class="my-8 text-4xl font-bold text-center">Related Products</h1>
        <div class="relative w-full">
            <div class="relative swiper multiple-slide-carousel swiper-container">
                <div class="mb-16 swiper-wrapper" data-aos="zoom-in" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
                    @foreach($relatedProducts as $relatedProduct)
                    <div class="swiper-slide">
                        @if($relatedProduct->images->isNotEmpty())
                        @php
                        $firstImage = $relatedProduct->images->first();
                        @endphp
                        <img class=" object-contain  h-[20rem] w-full" src="{{ asset('images/uploads/product_images/' . $firstImage->image_path) }}" alt="{{ $relatedProduct->product_name }}">
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="flex items-center justify-center gap-8 lg:justify-start">
                <button id="slider-button-left" class="swiper-button-prev group !p-2 flex justify-center items-center border border-solid border-transparent !w-12 !h-12 transition-all duration-500 !top-2/4 !-translate-y-8 bg-gray-200 opacity-60 !left-5 text-gray-800 " data-carousel-prev></button>
                <button id="slider-button-right" class="swiper-button-next group !p-2 flex justify-center items-center border border-solid border-transparent !w-12 !h-12 transition-all duration-500 !top-2/4 !-translate-y-8 bg-gray-200 opacity-60 !right-5 text-gray-800 " data-carousel-next></button>
            </div>
        </div>
    </div>

    <script>
        var swiper = new Swiper(".multiple-slide-carousel", {
            loop: true,
            slidesPerView: 1,
            spaceBetween: 20,
            autoplay: {
                delay: 3000, // Change the delay to your desired value in milliseconds
                disableOnInteraction: false, // Optional: If true, autoplay will be disabled after user interactions
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                990: {
                    slidesPerView: 1,
                    spaceBetween: 0
                },
                1028: {
                    slidesPerView: 2,
                    spaceBetween: 30
                },
                1920: {
                    slidesPerView: 4,
                    spaceBetween: 30
                },
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select all thumbnail images
            const thumbnails = document.querySelectorAll('.thumbnail');

            // Add click event listener to each thumbnail
            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    // Get the image URL from data attribute
                    const imageUrl = this.getAttribute('data-image');
                    // Update the src attribute of the main image
                    document.getElementById('main-image').src = imageUrl;
                });
            });
        });
    </script>
</div>



@include('layouts.member')
@include('layouts.footer')
@endsection
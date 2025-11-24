@extends('layouts.main-app')

@section('title', 'Home')

@section('content')

@include('layouts.navbar')

<div class="relative w-full">
    <div class="swiper default-carousel swiper-container">
        <div class="swiper-wrapper">

            @foreach($banner as $banners)
            <div class="swiper-slide">
                <div class="relative h-[60rem] ">
                    <img class="absolute z-0 object-cover object-right w-full h-full md:object-left" src="{{asset($banners->banner_image)}}" alt="">
                    <div class="container relative max-w-screen-xl mx-auto py-[20rem] ">
                        <div class="grid justify-end grid-cols-1 px-4 md:grid-cols-2">
                            <div>
                            </div>
                            <div class="relative z-50 space-y-12 text-right">
                                <div class="space-y-4 ">
                                    <h1 class="text-6xl font-semibold">
                                        {{$banners->title}}
                                    </h1>
                                    <p clas="font-semibold text-xl">
                                        {{$banners->description}}
                                    </p>
                                </div>
                                <div class="flex flex-col justify-end space-x-4 space-y-4 md:flex-row md:space-y-0">
                                    <div>
                                        <a href="{{url('/be_our_member_distributors')}}">
                                            <button class="bg-gradient-to-r from-[#f590b0] to to-[#f56e98] rounded-lg px-12 py-2 text-white border-2 border-white">BECOME A MEMBER</button>
                                        </a>
                                    </div>
                                    <div>
                                        <a href="{{url('/products')}}">
                                            <button class="bg-gradient-to-r from-[#f590b0] to to-[#f56e98] rounded-lg px-12 py-2 text-white border-2 border-white">BROWSE COLLECTION</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach


        </div>
        <div class=" swiper-pagination"></div>
    </div>
</div>
<style>
    .swiper-pagination-bullet {
        width: 12px;
        height: 12px;
        background-color: transparent;
        border: 2px solid #f590b0;
        /* Pink border color */
        border-radius: 50%;
        opacity: 1;
    }

    .swiper-pagination-bullet-active {
        background-color: #f590b0;
        /* Pink background color for the active bullet */
        border-color: transparent;
        /* Hide border on active bullet */
        box-shadow: 0 0 10px rgba(245, 144, 176, 0.5);
        /* Pink shadow for a glow effect */
    }
</style>
<script>
    var swiper = new Swiper(".default-carousel", {
        loop: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        autoplay: {
            delay: 3000, // Change the delay to your desired value in milliseconds
            disableOnInteraction: false, // Optional: If true, autoplay will be disabled after user interactions
        },
    });
</script>


<div class="container max-w-screen-xl mx-auto my-20">
    <div class="relative w-full" data-aos="fade-up" data-aos-duration="1500">
        <div class="relative swiper multiple-slide-carousel swiper-container">
            <div class="mb-16 swiper-wrapper">
                @foreach($partners as $partner)
                <div class="swiper-slide">
                    <img src="{{asset($partner->partners_image)}}" alt="">
                </div>
                @endforeach
            </div>
        </div>
        <script>
            var swiper = new Swiper(".multiple-slide-carousel", {
                loop: true,
                slidesPerView: 3,
                spaceBetween: 20,
                autoplay: {
                    delay: 3000, // Change the delay to your desired value in milliseconds
                    disableOnInteraction: false, // Optional: If true, autoplay will be disabled after user interactions
                },
                navigation: {
                    nextEl: ".multiple-slide-carousel .swiper-button-next",
                    prevEl: ".multiple-slide-carousel .swiper-button-prev",
                },
                breakpoints: {
                    1920: {
                        slidesPerView: 5,
                        spaceBetween: 30
                    },
                    1028: {
                        slidesPerView: 2,
                        spaceBetween: 30
                    },
                    990: {
                        slidesPerView: 1,
                        spaceBetween: 0
                    }
                }
            });
        </script>
    </div>
</div>

<div class="relative h-[60rem]" data-aos="fade-up" data-aos-duration="1500">
    <img class="absolute z-0 object-cover w-full h-full" src="{{asset('images/homepage/SYGLOW-BG-1.png')}}" alt="">
    <div class="container relative max-w-screen-xl mx-auto py-[10rem]">
        <div class="grid items-center grid-cols-1 space-y-12 md:space-y-0 md:grid-cols-2">
            <div>
                <p class="px-4 text-center urbanist">
                    SY Glow has transformed my skin. Since using their products, I've noticed a brighter, more even-toned complexion, and my skin feels so much healthier and more hydrated. The best part? Their products are gentle enough for even the most sensitive skin.
                    <br>
                    <br>
                    Whether you're looking for a hydrating boost or want to achieve a luminous glow, SY Glow has something for everyone. Explore their amazing range of products and discover your own inner radiance!
                    <br>
                    <br>
                    See you glowing.
                </p>
            </div>
            <div>
                <img src="{{asset('images/homepage/img-pres.png')}}" alt="">
            </div>
        </div>

    </div>
</div>

<div class="container relative max-w-screen-xl mx-auto text-center" data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
    <div class="flex justify-center">
        <img class="absolute z-0 w-[40rem] h-[15rem]" src="{{asset('images/Featured.png')}}" alt="">
    </div>
    <div class="py-20">
        <h1 class="text-xl text-[#f590b0]">CUSTOMER'S CHOICE</h1>
        <h1 class="text-4xl">
            Hot Selling Products
        </h1>
    </div>
</div>


<div class="relative w-full" data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
    <!-- Swiper Container -->
    <div class="relative swiper centered-slide-carousel swiper-container">
        <div class="swiper-wrapper" data-aos="zoom-in" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">

            @foreach ($hot_selling_products as $hot_selling_product)
            @php
            $hasImages = $hot_selling_product->images->isNotEmpty();
            $firstImage = $hasImages ? $hot_selling_product->images->first() : null;
            @endphp
            @if($hasImages)
            <div class="flex flex-col swiper-slide group" data-aos="zoom-in" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
                <!-- Product Image with Overlaid Actions -->
                <div class="relative w-full h-full">
                    <a href="{{ url('products_details/' . $hot_selling_product->id) }}">
                        <img class="object-cover w-full h-full" src="{{ asset('images/uploads/product_images/' . $firstImage->image_path) }}" alt="{{ $hot_selling_product->product_name }}">
                    </a>

                    <!-- Action Icons -->
                    <div class="absolute z-50 space-y-8 right-4 top-6 flex flex-col">
                        <!-- Product Details Link -->
                        <div class="relative group icon-container">
                            <a class="transition-opacity duration-300 opacity-0 group-hover:opacity-100" href="{{ url('products_details/' . $hot_selling_product->id) }}">
                                <img src="{{ asset('images/prod-view.png') }}" alt="View Product">
                                <span class="absolute top-0 z-50 px-2 py-1 text-xs text-gray-500 transition-opacity duration-300 transform -translate-x-1/2 -translate-y-full bg-gray-200 rounded icon-text left-1/2">
                                    View Product
                                </span>
                            </a>
                        </div>

                        <!-- Wishlist Button -->
                        <div class="relative z-10 transition-opacity duration-300 opacity-0 group icon-container group-hover:opacity-100">
                            @if (Auth::check())
                            <form action="{{ route('wishlist.store', ['product_id' => $hot_selling_product->id, 'customer_id' => Auth::user()->id]) }}" method="POST">
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
                        @if($hot_selling_product->availability !== 'out_of_stock')
                        <button
                            id="open-modal"
                            data-product-id="{{ $hot_selling_product->id }}"
                            data-product-name="{{ $hot_selling_product->product_name }}"
                            data-product-price="{{ $hot_selling_product->product_price }}"
                            data-product-image="{{ asset('images/uploads/product_images/' . $firstImage->image_path) }}"
                            class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-xl w-full py-2 text-white transition-opacity duration-300 opacity-0 group-hover:opacity-100">
                            ADD TO CART
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Product Info -->
                <div class="my-8 space-y-2 px-2">
                    <h1 class="text-sm">{{ $hot_selling_product->product_name }}</h1>
                    <p class="text-[#f590b0]">₱{{ $hot_selling_product->product_price }}</p>
                    <div class="flex items-center text-[#fdd900]">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class='bx bxs-star {{ $i <= round($hot_selling_product->averageRating) ? "text-[#fdd900]" : "text-gray-300" }}'></i>
                            @endfor
                            <span class="ml-2 text-gray-600">({{ number_format($hot_selling_product->averageRating, 1) }})</span>
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
                .icon-container {
                    position: relative;
                    display: inline-block;
                }

                .icon-text {
                    position: absolute;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                    white-space: nowrap;
                    /* Ensures the text appears on one line */
                    z-index: 10;
                }

                /* Hover effect to show text */
                .icon-container:hover .icon-text {
                    opacity: 1;
                }
            </style>
        </div>
    </div>
</div>



<div class="grid grid-rows-1 gap-4 lg:grid-rows-2 lg:grid-flow-col">
    <div class="relative w-full h-full group" data-aos="zoom-in" data-aos-duration="1500">
        <img class="object-cover w-full h-full" src="{{asset('images/img2.png')}}" alt="">
        <div class="absolute bottom-0 bg-gradient-to-t from-[#f590b0] to-transparent h-full w-full opacity-0 group-hover:opacity-50 transition-opacity duration-300">
        </div>
        <div class="absolute inset-0 flex flex-col items-center justify-center h-full">
            <h1 class="transition-opacity duration-300 opacity-0 group-hover:opacity-100">PURIFY & PREP</h1>
            <h1 class="text-6xl text-white transition-opacity duration-300 opacity-0 group-hover:opacity-100">Cleansers</h1>
            <div class="absolute transition-opacity duration-300 opacity-0 group-hover:opacity-100 bottom-8 ">
                <a href="{{url('/products')}}">
                    <button class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg px-32 py-2 text-white border-2 border-white">
                        SHOP NOW
                    </button>
                </a>
            </div>
        </div>
    </div>

    <div class="relative w-full h-full group " data-aos="zoom-in" data-aos-duration="1500">
        <img class="object-cover w-full h-full " src="{{asset('images/img3.png')}}" alt="">
        <div class="absolute bottom-0 bg-gradient-to-t from-[#f590b0] to-transparent h-full w-full opacity-0 group-hover:opacity-50 transition-opacity duration-300">
        </div>
        <div class="absolute inset-0 flex flex-col items-center justify-center h-full">
            <h1 class="transition-opacity duration-300 opacity-0 group-hover:opacity-100">TARGETED TREATMENTS</h1>
            <h1 class="text-6xl text-white transition-opacity duration-300 opacity-0 group-hover:opacity-100">Serums</h1>
            <div class="absolute transition-opacity duration-300 opacity-0 group-hover:opacity-100 bottom-8 ">
                <a href="{{url('/products')}}">
                    <button class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg px-32 py-2 text-white border-2 border-white">
                        SHOP NOW
                    </button>
                </a>
            </div>
        </div>
    </div>
    <div class="relative w-full h-full row-span-2 group" data-aos="zoom-in" data-aos-duration="1500">
        <img class="object-cover w-full h-full " src="{{asset('images/img1.png')}}" alt="">


        <div class="absolute bottom-0 bg-gradient-to-t from-[#f590b0] to-transparent h-full w-full opacity-0 group-hover:opacity-50 transition-opacity duration-300">
        </div>
        <div class="absolute inset-0 flex flex-col items-center justify-center h-full">
            <h1 class="transition-opacity duration-300 opacity-0 group-hover:opacity-100">NOURISH & HYDRATE</h1>
            <h1 class="text-6xl text-white transition-opacity duration-300 opacity-0 group-hover:opacity-100">Moisturizers</h1>
            <div class="absolute transition-opacity duration-300 opacity-0 group-hover:opacity-100 bottom-8 ">
                <a href="{{url('/products')}}">
                    <button class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg px-32 py-2 text-white border-2 border-white">
                        SHOP NOW
                    </button>
                </a>
            </div>
        </div>
    </div>


    <div class="relative w-full h-full group " data-aos="zoom-in" data-aos-duration="1500">
        <img class="object-cover w-full h-full " src="{{asset('images/img4.png')}}" alt="">


        <div class="absolute bottom-0 bg-gradient-to-t from-[#f590b0] to-transparent h-full w-full opacity-0 group-hover:opacity-50 transition-opacity duration-300">
        </div>
        <div class="absolute inset-0 flex flex-col items-center justify-center h-full">
            <h1 class="transition-opacity duration-300 opacity-0 group-hover:opacity-100">SHIELD & PROTECT</h1>
            <h1 class="text-6xl text-white transition-opacity duration-300 opacity-0 group-hover:opacity-100">Sunscreens</h1>
            <div class="absolute transition-opacity duration-300 opacity-0 group-hover:opacity-100 bottom-8 ">
                <a href="{{url('/products')}}">
                    <button class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg px-32 py-2 text-white border-2 border-white">
                        SHOP NOW
                    </button>
                </a>
            </div>
        </div>

    </div>

    <div class="relative w-full h-full group " data-aos="zoom-in" data-aos-duration="1500">
        <img class="object-cover w-full h-full" src="{{asset('images/img5.png')}}" alt="">

        <div class="absolute bottom-0 bg-gradient-to-t from-[#f590b0] to-transparent h-full w-full opacity-0 group-hover:opacity-50 transition-opacity duration-300">
        </div>
        <div class="absolute inset-0 flex flex-col items-center justify-center h-full">
            <h1 class="transition-opacity duration-300 opacity-0 group-hover:opacity-100">BALANCE & REFINE</h1>
            <h1 class="text-6xl text-white transition-opacity duration-300 opacity-0 group-hover:opacity-100">Toners</h1>
            <div class="absolute transition-opacity duration-300 opacity-0 group-hover:opacity-100 bottom-8 ">
                <a href="{{url('/products')}}">
                    <button class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg px-32 py-2 text-white border-2 border-white">
                        SHOP NOW
                    </button>
                </a>
            </div>
        </div>
    </div>

</div>


@include('layouts.benefits')



<div class="container relative max-w-screen-xl mx-auto my-20" data-aos="fade-up"
    data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
    <div class="flex justify-center">
        <img class="absolute z-0 w-[40rem] h-[15rem]" src="{{asset('images/Products.png')}}" alt="">
    </div>
    <div class="py-20 text-center">
        <h1 class="text-xl text-[#f590b0]">PREMIUM PRODUCTS</h1>
        <h1 class="text-6xl">
            New Arrivals
        </h1>
    </div>

    <div class="grid grid-cols-1 gap-4 px-4 md:grid-cols-2 lg:grid-cols-3">

        @foreach ($products->take(18) as $product)
        <div class="relative grid grid-cols-3 w-full h-full shadow-lg group" data-aos="zoom-in" data-aos-duration="1500">

            <div class="absolute space-y-2 right-4 top-2 flex flex-col">
                <!-- View Details Icon -->
                <div class="relative group icon-container">
                    <a class="transition-opacity duration-300 opacity-0 group-hover:opacity-100" href="{{ url('products_details/' . $product->id) }}">
                        <img src="{{ asset('images/prod-view.png') }}" alt="View Details">
                        <span class="absolute top-0 px-2 py-1 text-xs text-gray-500 transform -translate-x-1/2 -translate-y-full bg-gray-200 rounded icon-text left-1/2">
                            View Product
                        </span>
                    </a>
                </div>

                <!-- Wishlist Icon -->
                <div class="relative group icon-container">
                    @if (Auth::check())
                    <form action="{{ route('wishlist.store', ['product_id' => $product->id, 'customer_id' => Auth::user()->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <button type="submit" class="transition-opacity duration-300 opacity-0 group-hover:opacity-100">
                            <img src="{{ asset('images/prod-heart.png') }}" alt="Add to Wishlist">
                            <span class="absolute top-0 px-2 py-1 text-xs text-gray-500 transform -translate-x-1/2 -translate-y-full bg-gray-200 rounded icon-text left-1/2">
                                Add Wishlist
                            </span>
                        </button>
                    </form>
                    @else
                    <a href="{{ url('wishlist') }}" class="transition-opacity duration-300 opacity-0 group-hover:opacity-100">
                        <img src="{{ asset('images/prod-heart.png') }}" alt="Wishlist">
                        <span class="absolute top-0 px-2 py-1 text-xs text-gray-500 transform -translate-x-1/2 -translate-y-full bg-gray-200 rounded icon-text left-1/2">
                            Add Wishlist
                        </span>
                    </a>
                    @endif
                </div>

                <!-- Add to Cart Icon -->
                @if($product->availability !== 'out_of_stock')
                <div class="relative group icon-container">
                    <button id="open-modal"
                        data-product-id="{{ $product->id }}"
                        data-product-name="{{ $product->product_name }}"
                        data-product-price="{{ $product->product_price }}"
                        data-product-image="{{ asset('images/uploads/product_images/' . $firstImage->image_path) }}"
                        class="transition-opacity duration-300 opacity-0 group-hover:opacity-100">
                        <img src="{{ asset('images/prod-shopping-cart.png') }}" alt="Add to Cart">
                        <span class="absolute top-0 px-2 py-1 text-xs text-gray-500 transform -translate-x-1/2 -translate-y-full bg-gray-200 rounded icon-text left-1/2">
                            Add Cart
                        </span>
                    </button>
                </div>
                @endif
            </div>

            <!-- Product Image -->
            @if($product->images->isNotEmpty())
            @php
            $firstImage = $product->images->first();
            @endphp
            <div class="h-[10rem] w-[10rem]">
                <a href="{{ url('products_details/' . $product->id) }}">
                    <img class="w-full h-full object-cover" src="{{ asset('images/uploads/product_images/' . $firstImage->image_path) }}" alt="Product Image">
                </a>
            </div>
            @endif

            <!-- Product Info -->
            <a class="col-span-2" href="{{ url('products_details/' . $product->id) }}">
                <div class="flex flex-col justify-center w-full h-full px-4 space-y-2 bg-white">
                    <h1 class="font-semibold">{{ $product->product_name }}</h1>
                    <p class="text-[#f590b0]">₱{{ $product->product_price }}</p>
                    <div class="flex items-center text-[#fdd900]">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class='bx bxs-star {{ $i <= round($product->averageRating) ? "text-[#fdd900]" : "text-gray-300" }}'></i>
                            @endfor
                            <span class="ml-2 text-gray-600">({{ number_format($product->averageRating, 1) }})</span>
                    </div>
                </div>
            </a>

        </div>
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

            /* Show text when hovered */
            .icon-container:hover .icon-text {
                opacity: 1;
            }
        </style>

    </div>
    <div class="flex justify-center px-4 my-8">
        <a href="{{url('/products')}}">
            <button class="bg-gradient-to-r from-[#f590b0] to to-[#f56e98] rounded-lg px-12 py-2 text-white">VIEW ALL PRODUCTS</button>
        </a>
    </div>
</div>


<script>
    var swiper = new Swiper(".centered-slide-carousel", {
        centeredSlides: true,
        paginationClickable: true,
        loop: true,
        spaceBetween: 30,
        slideToClickedSlide: true,
        autoplay: {
            delay: 3000, // Change the delay to your desired value in milliseconds
            disableOnInteraction: false, // Optional: If true, autoplay will be disabled after user interactions
        },
        pagination: {
            el: ".centered-slide-carousel .swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            1920: {
                slidesPerView: 5.5,
                spaceBetween: 30
            },
            1028: {
                slidesPerView: 5.5,
                spaceBetween: 10
            },

            768: {
                slidesPerView: 3.5,
                spaceBetween: 10
            },

            425: {
                slidesPerView: 1.5,
                spaceBetween: 5
            },


        }
    });
</script>

@include('layouts.member')
@include('layouts.footer')
@endsection
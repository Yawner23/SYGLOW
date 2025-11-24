@extends('layouts.main-app')

@section('title', 'About Us')

@section('content')

@include('layouts.navbar')

<div class="relative h-[20rem]">
    <img class="absolute z-0 object-cover w-full h-full " src="{{asset('images/All-Banner.png')}}" alt="">
    <div class="container relative max-w-screen-xl py-32 mx-auto">
        <div class="flex flex-col items-center justify-center space-y-4">
            <h1 class="text-5xl font-semibold">ABOUT US</h1>
            <h1 class="text-xl">Home >> <span class="text-[#f56e98]">About Us</span> </h1>
        </div>
    </div>
</div>

@include('layouts.benefits')

<div class="container h-full max-w-screen-xl mx-auto my-60 md:my-40">
    <div class="relative w-full" data-aos="zoom-in" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
        <div class="swiper default-carousel swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="relative w-full h-full group">
                        <img class="object-cover w-full h-full" src="{{asset('images/aboutUs-img1.png')}}" alt="">
                        <div class="absolute bottom-0 bg-gradient-to-t from-[#f590b0] to-transparent h-full w-full opacity-0 group-hover:opacity-50 transition-opacity duration-300">
                        </div>
                        <div class="absolute inset-0 flex flex-col items-center justify-center h-full">
                            <h1 class="opacity-100">PURIFY & PREP</h1>
                            <h1 class="text-6xl text-white opacity-100">Cleansers</h1>
                            <div class="absolute transition-opacity duration-300 opacity-0 bottom-8 group-hover:opacity-100">
                                <a href="{{url('/products')}}">
                                    <button class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg px-32 py-2 text-white border-2 border-white">
                                        SHOP NOW
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="relative w-full h-full group">
                        <img class="object-cover w-full h-full" src="{{asset('images/aboutUs-img2.png')}}" alt="">
                        <div class="absolute bottom-0 bg-gradient-to-t from-[#f590b0] to-transparent h-full w-full opacity-0 group-hover:opacity-50 transition-opacity duration-300">
                        </div>
                        <div class="absolute inset-0 flex flex-col items-center justify-center h-full">
                            <h1 class="opacity-100">NOURISH & HYDRATE</h1>
                            <h1 class="text-6xl text-black opacity-100">Moisturizers</h1>
                            <div class="absolute transition-opacity duration-300 opacity-0 bottom-8 group-hover:opacity-100">
                                <a href="{{url('/products')}}">
                                    <button class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg px-32 py-2 text-white border-2 border-white">
                                        SHOP NOW
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="relative w-full h-full group">
                        <img class="object-cover w-full h-full" src="{{asset('images/aboutUs-img1.png')}}" alt="">
                        <div class="absolute bottom-0 bg-gradient-to-t from-[#f590b0] to-transparent h-full w-full opacity-0 group-hover:opacity-50 transition-opacity duration-300">
                        </div>
                        <div class="absolute inset-0 flex flex-col items-center justify-center h-full">
                            <h1 class="opacity-100">PURIFY & PREP</h1>
                            <h1 class="text-6xl text-white opacity-100">Cleansers</h1>
                            <div class="absolute transition-opacity duration-300 opacity-0 bottom-8 group-hover:opacity-100">
                                <a href="{{url('/products')}}">
                                    <button class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg px-32 py-2 text-white border-2 border-white">
                                        SHOP NOW
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-center gap-8 lg:justify-start">
                <button id="slider-button-left" class="swiper-button-prev group !p-2 flex justify-center items-center border border-solid border-transparent !w-12 !h-12 transition-all duration-500 !top-2/4 !-translate-y-8 bg-gray-200 opacity-60 !left-5  text-gray-800 " data-carousel-prev>
                </button>
                <button id="slider-button-right" class="swiper-button-next group !p-2 flex justify-center items-center border border-solid bordertransparent !w-12 !h-12 transition-all duration-500 !top-2/4 !-translate-y-8 bg-gray-200 opacity-60  !right-5  text-gray-800 " data-carousel-next>
                </button>
            </div>
        </div>
    </div>
    <script>
        var swiper = new Swiper(".default-carousel", {
            loop: true,
            slidesPerView: 2,
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

    <div class="grid grid-cols-1 gap-40 my-20 overflow-hidden md:grid-cols-2">
        <div class="flex flex-col justify-center space-y-8" data-aos="fade-right" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
            <div class="flex justify-center">
                <img src="{{asset('images/aboutUs-img3.png')}}" alt="">
            </div>
            <div>
                <p class="text-center">
                   SY Glow was born from a simple desire: to help people embrace their natural beauty and unlock a healthy. luminous glow. We believe that true beauty lies in celebrating your unique skin tone and enhancing its natural radiance.
                </p>
            </div>
        </div>
        <div class="flex flex-col justify-center space-y-8 mt-60" data-aos="fade-left" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
            <div class="flex justify-center ">
                <img src="{{asset('images/aboutUs-img4.png')}}" alt="">
            </div>
            <div>
                <p class="text-center">
                    Our commitment to quality and effectiveness is unwavering. We source the finest ingredients and utilize advanced formulations to create products that deliver exceptional results. Whether you're seeking a daily skincare routine or a targeted solution for specific concerns, SY Glow has something to offer.
                </p>
            </div>
        </div>
    </div>
</div>


<div class="grid grid-rows-1 gap-4 lg:grid-rows-2 lg:grid-flow-col">
    <div class="relative w-full h-full " data-aos="zoom-in" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
        <img class="object-cover w-full h-full" src="{{asset('images/aboutUs-img5.png')}}" alt="">
    </div>

    <div class="relative w-full h-full " data-aos="zoom-in" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
        <img class="object-cover w-full h-full" src="{{asset('images/aboutUs-img9.png')}}" alt="">
    </div>
    <div class="relative w-full h-full " data-aos="zoom-in" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
        <img class="object-cover w-full h-full" src="{{asset('images/aboutUs-img6.png')}}" alt="">
    </div>

    <div class="relative w-full h-full " data-aos="zoom-in" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
        <img class="object-cover w-full h-full" src="{{asset('images/aboutUs-img10.png')}}" alt="">
    </div>

    <div class="relative w-full h-full row-span-2 " data-aos="zoom-in" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
        <div class="flex flex-col items-center justify-center w-full h-full space-y-8 text-center align-center">
            <div class="flex justify-center">
                <img class="object-cover " src="{{asset('images/logo-footer.png')}}" alt="">
            </div>
            <h1 class="text-4xl ">
                Clean Charm
                <br>
                that we Admire
            </h1>
            <a href="{{url('/products')}}">
                <button class="bg-gradient-to-r from-[#f590b0] to to-[#f56e98] rounded-lg px-12 py-2 text-white">SHOP NOW</button>
            </a>
        </div>
    </div>

    <div class="relative w-full h-full " data-aos="zoom-in" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
        <img class="object-cover w-full h-full" src="{{asset('images/aboutUs-img7.png')}}" alt="">
    </div>

    <div class="relative w-full h-full " data-aos="zoom-in" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
        <img class="object-cover w-full h-full" src="{{asset('images/aboutUs-img11.png')}}" alt="">
    </div>

    <div class="relative w-full h-full " data-aos="zoom-in" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
        <img class="object-cover w-full h-full" src="{{asset('images/aboutUs-img8.png')}}" alt="">
    </div>


    <div class="relative w-full h-full " data-aos="zoom-in" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
        <img class="object-cover w-full h-full" src="{{asset('images/aboutUs-img12.png')}}" alt="">
    </div>

</div>

<div class="container max-w-screen-lg mx-auto my-12 text-center" data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
    <p>
       Join us on a captivating journey to uncover your most radiant self with SY Glow. Explore our curated collection of beauty essentials, designed to illuminate your natural beauty and enhance your glow from within. Each product is crafted to transform your skincare routine, revealing a luminous, confident you.


        <br>
        <br>
       Dive into the world of SY Glow and experience the power of beauty that shines from the inside out. Your journey to radiance starts here—embrace your glow today!
    </p>

</div>

@include('layouts.member')
@include('layouts.footer')
@endsection
@extends('layouts.main-app')

@section('title', 'Contact Us')

@section('content')

@include('layouts.navbar')

<div class="relative h-[20rem]">
    <img class="absolute object-cover w-full h-full " src="{{asset('images/All-Banner.png')}}" alt="">
    <div class="container relative max-w-screen-xl py-32 mx-auto">
        <div class="flex flex-col items-center justify-center space-y-4">
            <h1 class="text-5xl font-semibold">Contact Us</h1>
            <h1 class="text-xl">Home >> <span class="text-[#f56e98]">Contact Us</span> </h1>
        </div>
    </div>
</div>
<div class="container max-w-screen-xl mx-auto">
    <div class="flex justify-start">
        <img class="absolute object-cover md:w-[40rem] h-[15rem]" src="{{asset('images/Contact-Us.png')}}" alt="">
    </div>
</div>

<div class="relative w-full h-full">
    <img class="absolute object-cover w-full h-full" src="{{asset('images/Golden-Sparkling-Wave.png')}}" alt="">

    <div class="container relative max-w-screen-xl px-4 mx-auto my-12" data-aos="zoom-in" data-aos-duration="1500">
        @if(session('success'))
        <div class="p-4 mb-6 text-white bg-green-500 rounded">
            {{ session('success') }}
        </div>
        @endif
        <h1 class="text-5xl font-bold">Get In Touch</h1>

        <form action="{{ route('contacts.store_contact_us') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-8 my-4 md:grid-cols-3">
                <div class="flex flex-col">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" class="p-2">
                    @error('name')
                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="p-2">
                    @error('email')
                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col">
                    <label for="phone_number">Phone Number</label>
                    <input type="tel" id="phone_number" name="phone_number" class="p-2">
                    @error('phone_number')
                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col md:col-span-3">
                    <label for="comment">Comment</label>
                    <textarea rows="8" id="comment" name="comment" class="p-2"></textarea>
                    @error('comment')
                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITEKEY') }}"></div>
                    @error('g-recaptcha-response')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="flex items-center justify-center my-12">
                <button type="submit" class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg w-full md:w-60 py-2 text-white border-2 border-white">
                    Send
                </button>
            </div>
        </form>

    </div>

    <div class="container relative max-w-screen-xl mx-auto py-20" data-aos="zoom-in" data-aos-duration="1500">

        <iframe class="w-full" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3959.1681466510367!2d125.63352577571504!3d7.106503216112525!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x32f96d65a9ac63f1%3A0x7c4976dd028b3501!2sSYJL%20RICH8%20TRADING!5e0!3m2!1sen!2sph!4v1727944777964!5m2!1sen!2sph" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</div>

@include('layouts.member')
@include('layouts.footer')

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection
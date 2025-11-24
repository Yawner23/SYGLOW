@extends('layouts.sidebar')

@section('title', 'Home')

@section('content')
<div class="flex flex-col justify-between p-8 text-4xl md:flex-row">
    <h1>Profile</h1>
    <h1>Welcome, {{ Auth::user()->distributor->name }}!</h1>
</div>
<div class="flex flex-col justify-between p-8 md:flex-row ">
    <div class="flex items-center space-x-8">
        <img class="rounded-full h-[6rem] object-cover w-[6rem]" src="{{ asset('uploads/profile_pictures/' . (Auth::user()->distributor->profile_picture ?? 'default-avatar.jpg')) }}" alt="Profile Picture">
        <div>
            <h1 class="text-[#f590b0] text-4xl">{{ Auth::user()->distributor->name }}</h1>
            <p class="text-xl">{{ Auth::user()->id }}</p>
            <p class="text-xl">{{ Auth::user()->email }}</p>
        </div>
    </div>
    <div class="flex flex-col items-center p-8 space-y-2 md:space-y-0 md:p-0 md:flex-row">
        <div>
            <a href="{{ url('/distributor_id') }}">
                <button class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-xl w-60 py-2 text-white border-2 border-white"> VIEW ID</button>
            </a>
        </div>
        <div>
            <div class="relative inline-block text-left">
                <button id="dropdownButton" class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-xl w-60 py-2 text-white border-2 border-white">
                    DISTRIBUTORS
                    <svg class="inline w-5 h-5 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div id="dropdownMenu" class="absolute right-0 hidden mt-2 bg-white rounded-md shadow-lg w-60 ring-1 ring-black ring-opacity-5">
                    <div class="py-1">
                        <a href="#" id="uplineLink" class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#f56e98] hover:text-white">Upline</a>
                        <a href="#" id="downlineLink" class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#f56e98] hover:text-white">Downline</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section id="uplineSection" class="hidden">
    <div class="flex flex-col justify-between mb-4 md:flex-row">
        <div>
            <h1 class="text-4xl font-bold">Your Upline</h1>
        </div>
        <div class="flex flex-row items-center space-x-2">
            <form method="GET" action="{{ url()->current() }}" class="flex flex-col md:flex-row">
                <input class="rounded-2xl border-[#f590b0] shadow shadow-[#f590b0]" type="search" name="search" placeholder="Search Downlines" value="{{ request('search') }}">
                <button class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-xl w-full py-2 px-2 md:px-8 text-white border-2 border-white shadow-lg" type="submit">SEARCH</button>
            </form>
        </div>
    </div>
    <div class="container max-w-screen-lg">
        <div class="grid grid-cols-1 gap-12 md:grid-cols-3">
            @foreach($uplines as $upline)
            <div class="relative h-full shadow-lg">
                <img class="absolute right-2 top-4 z-10 object-cover w-[4rem] h-[4rem]" src="{{ asset('images/logo-footer.png') }}" alt="Logo">
                <img class="absolute z-0 object-cover w-full h-full" src="{{ asset('images/Distributor-Card.png') }}" alt="Distributor Card">
                <div class="relative flex flex-col items-center justify-center py-12">
                    <img class="rounded-full h-[6rem] object-cover w-[6rem]" src="{{ asset('uploads/profile_pictures/' . ($upline->profile_picture ?? 'default-avatar.jpg')) }}" alt="Distributor Picture">
                    <p>{{ $upline->id }}</p>
                    <p class="text-[#f56e98] text-2xl">{{ $upline->name }}</p>
                    <div class="flex items-center justify-center py-4 space-x-2">
                        <div><img src="{{ asset('images/icon-facebook.png') }}" alt="Facebook"></div>
                        <div><img src="{{ asset('images/icon-tiktok.png') }}" alt="TikTok"></div>
                        <div><img src="{{ asset('images/icon-instagram.png') }}" alt="Instagram"></div>
                    </div>
                    <ul class="space-y-2">
                        <li class="flex items-center space-x-4">
                            <img src="{{ asset('images/phone-call.png') }}" alt="Phone">
                            <p>{{ $upline->phone_number }}</p>
                        </li>
                        <li class="flex items-center space-x-4">
                            <img src="{{ asset('images/route.png') }}" alt="Location">
                            <p>{{ $upline->location }}</p>
                        </li>
                    </ul>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section id="downlineSection" class="hidden">
    <div class="flex flex-col justify-between mb-4 md:flex-row">
        <div>
            <h1 class="text-4xl font-bold">Your Downline</h1>
        </div>
        <div class="flex flex-row items-center space-x-2">
            <form method="GET" action="{{ url()->current() }}" class="flex flex-col md:flex-row">
                <input class="rounded-2xl border-[#f590b0] shadow shadow-[#f590b0]" type="search" name="search" placeholder="Search Downlines" value="{{ request('search') }}">
                <button class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-xl w-full py-2 px-2 md:px-8 text-white border-2 border-white shadow-lg" type="submit">SEARCH</button>
            </form>
        </div>
    </div>
    <div class="container max-w-screen-lg">
        <div class="grid grid-cols-1 gap-12 md:grid-cols-3">
            @foreach($downlines as $downline)
            <div class="relative h-full shadow-lg">
                <img class="absolute right-2 top-4 z-10 object-cover w-[4rem] h-[4rem]" src="{{ asset('images/logo-footer.png') }}" alt="Logo">
                <img class="absolute z-0 object-cover w-full h-full" src="{{ asset('images/Distributor-Card.png') }}" alt="Distributor Card">
                <div class="relative flex flex-col items-center justify-center py-12">
                    <img class="rounded-full h-[6rem] object-cover w-[6rem]" src="{{ asset('uploads/profile_pictures/' . ($downline->profile_picture ?? 'default-avatar.jpg')) }}" alt="Distributor Picture">
                    <p>{{ $downline->id }}</p>
                    <p class="text-[#f56e98] text-2xl">{{ $downline->name }}</p>
                    <div class="flex items-center justify-center py-4 space-x-2">
                        <div><img src="{{ asset('images/icon-facebook.png') }}" alt="Facebook"></div>
                        <div><img src="{{ asset('images/icon-tiktok.png') }}" alt="TikTok"></div>
                        <div><img src="{{ asset('images/icon-instagram.png') }}" alt="Instagram"></div>
                    </div>
                    <ul class="space-y-2">
                        <li class="flex items-center space-x-4">
                            <img src="{{ asset('images/phone-call.png') }}" alt="Phone">
                            <p>{{ $downline->phone_number }}</p>
                        </li>
                        <li class="flex items-center space-x-4">
                            <img src="{{ asset('images/route.png') }}" alt="Location">
                            <p>{{ $downline->location }}</p>
                        </li>
                    </ul>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<script>
    const dropdownButton = document.getElementById('dropdownButton');
    const dropdownMenu = document.getElementById('dropdownMenu');
    const uplineLink = document.getElementById('uplineLink');
    const downlineLink = document.getElementById('downlineLink');
    const uplineSection = document.getElementById('uplineSection');
    const downlineSection = document.getElementById('downlineSection');

    dropdownButton.addEventListener('click', () => {
        dropdownMenu.classList.toggle('hidden');
    });

    document.addEventListener('click', (event) => {
        if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.classList.add('hidden');
        }
    });

    uplineLink.addEventListener('click', (event) => {
        event.preventDefault();
        uplineSection.classList.remove('hidden');
        downlineSection.classList.add('hidden');
    });

    downlineLink.addEventListener('click', (event) => {
        event.preventDefault();
        downlineSection.classList.remove('hidden');
        uplineSection.classList.add('hidden');
    });
</script>
@endsection
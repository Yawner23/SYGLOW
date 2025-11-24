@extends('layouts.main-app')

@section('title', 'Member')

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

    <div class="container relative max-w-screen-xl mx-auto">
        <div class="flex flex-col items-center justify-center">
            <img class="" src="{{asset('images/Membership.png')}}" alt="">
            <h1 class="text-5xl font-bold -mt-[10rem]">Members</h1>
        </div>
        <div class="mt-40 overflow-hidden">
            <form id="filterForm" method="GET" action="{{ url('/be_our_member_sign_up') }}" class="flex flex-col justify-between px-4 space-y-8 lg:space-y-0 lg:flex-row">
                <div class="flex flex-col space-y-4 md:space-y-0 md:flex-row" data-aos="fade-right" data-aos-duration="1500">
                    <div>
                        <input type="text" name="search" value="{{ request('search') }}" class="w-full rounded-lg" placeholder="Search">
                    </div>
                    <div>
                        <button type="submit" class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg w-full px-12 py-2 text-white border-2 border-white">SEARCH</button>
                    </div>
                </div>
                <div class="flex flex-col items-center my-4 space-y-4 md:my-0 md:space-y-0 md:space-x-2 md:flex-row" data-aos="fade-left" data-aos-duration="1500">
                    <div class="relative inline-flex self-center">
                        <select name="region" class="pl-5 pr-10 text-lg text-white bg-[#f56e98] border-2 border-[#f56e98] rounded-lg appearance-none h-12 w-40 focus:outline-none" onchange="submitForm()">
                            <option value="">Select Region</option>
                            @foreach ($regions as $region)
                            <option value="{{ $region }}" {{ request('region') == $region ? 'selected' : '' }}>{{ $region }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="relative inline-flex self-center">
                        <select name="province" class="pl-5 pr-10 text-lg text-white bg-[#f56e98] border-2 border-[#f56e98] rounded-lg appearance-none h-12 w-40 focus:outline-none" onchange="submitForm()">
                            <option value="">Select Province</option>
                            @foreach ($provinces as $province)
                            <option value="{{ $province }}" {{ request('province') == $province ? 'selected' : '' }}>{{ $province }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="relative inline-flex self-center">
                        <select name="city" class="pl-5 pr-10 text-lg text-white bg-[#f56e98] border-2 border-[#f56e98] rounded-lg appearance-none h-12 w-40 focus:outline-none" onchange="submitForm()">
                            <option value="">Select City</option>
                            @foreach ($cities as $city)
                            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
            <h1 class="px-4 my-8">Showing All Results</h1>
        </div>
        <div class="grid grid-cols-1 gap-8 px-4 md:grid-cols-2 lg:grid-cols-4">
            @foreach ($be_our_member as $be_our_members)
            <div class="relative h-full shadow-lg" data-aos="zoom-in" data-aos-duration="1500">
                <img class="absolute right-2 top-4 z-10 object-cover w-[4rem]  h-[4rem]" src="{{asset('images/logo-footer.png')}}" alt="">
                <img class="absolute z-0 object-cover w-full h-full" src="{{asset('images/Distributor-Card.png')}}" alt="">
                <div class="relative flex flex-col items-center justify-center py-12">
                    <img class="rounded-full h-[6rem] object-cover w-[6rem]" src="{{ asset('uploads/profile_pictures/' . ($be_our_members->profile_picture ?? 'default-avatar.jpg')) }}">
                    <p>{{$be_our_members->id}}</p>
                    <p class="text-[#f56e98] text-2xl">{{$be_our_members->name}}</p>
                    <div class="flex items-center justify-center py-4 space-x-2">
                        <a href="{{  ($be_our_members->user->socialMediaAccounts->facebook ?? '#') }}" target="_blank">
                            <img src="{{asset('images/icon-facebook.png')}}" alt="">
                        </a>

                        <a href="{{  ($be_our_members->user->socialMediaAccounts->tiktok ?? '#') }}" target="_blank">
                            <img src="{{asset('images/icon-tiktok.png')}}" alt="">
                        </a>

                        <a href="{{  ($be_our_members->user->socialMediaAccounts->instagram ?? '#') }}" target="_blank">
                            <img src="{{asset('images/icon-instagram.png')}}" alt="">
                        </a>
                    </div>
                    <ul class="space-y-2">
                        <li class="flex items-center space-x-4">
                            <img src="{{asset('images/phone-call.png')}}" alt="">
                            <p>{{$be_our_members->contact_number}}</p>
                        </li>
                        <li class="flex items-center space-x-4">
                            <img src="{{asset('images/route.png')}}" alt="">
                            <p>{{$be_our_members->city}}</p>
                        </li>
                    </ul>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<script>
    function submitForm() {
        document.getElementById('filterForm').submit();
    }
</script>
@include('layouts.footer')
@endsection
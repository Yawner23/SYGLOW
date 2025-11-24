@extends('layouts.sidebar')

@section('title', 'Home')

@section('content')


<div class="flex flex-col justify-between p-4 md:flex-row">
    <h1 class="text-4xl font-semibold">Profile</h1>
    <h1 class="text-4xl font-semibold">
        Welcome,
        @if(Auth::user()->distributor)
        {{ Auth::user()->distributor->name }}!
        @else
        {{ Auth::user()->name }}!
        @endif
    </h1>

</div>
<div class="flex flex-col justify-end p-4 my-12 md:flex-row">
    <a href="{{url('/distributor_id')}}">
        <button class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg px-12 py-2 text-white border-2 border-white">VIEW ID</button>
    </a>
    <a href="{{url('/distributor_list')}}">
        <button class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg px-12 py-2 text-white border-2 border-white">VIEW DISTRIBUTORS</button>
    </a>
</div>
<div class="bg-[#fdebdd] rounded my-40 ">
    <div class="flex flex-col px-8 space-x-4 md:flex-row">
        <div class="flex flex-col items-center space-y-2">
            <div>
                <img class="w-[7rem] h-[7rem] shrink-0 inline-block rounded-full object-cover -mt-8" src="{{ asset('uploads/profile_pictures/' . (Auth::user()->distributor->profile_picture ?? 'default-avatar.jpg')) }}" alt="avatar image">
            </div>
            <div>
                <a href="{{url('/distributor_edit_profile')}}">
                    <button for="profile_picture" class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg w-40 py-2 text-white border-2 border-white cursor-pointer block text-center">
                        EDIT PROFILE
                    </button>
                </a>
            </div>
        </div>
        <div class="flex items-center justify-between w-full ">
            <div class="flex flex-col my-4 text-left">
                <h1 class="text-4xl font-semibold">
                    Welcome,
                    @if(Auth::user()->distributor)
                    {{ Auth::user()->distributor->name }}!
                    @else
                    {{ Auth::user()->name }}!
                    @endif
                </h1>

                <p class="font-bold "> {{ Auth::user()->id }}</p>
                <p class=""> {{ Auth::user()->email }}</p>
            </div>
            <div>
                <h1 class="text-xl">
                    <span class="font-bold">Referral Code:</span>
                    @if(Auth::user()->distributor)
                    {{ Auth::user()->distributor->code }}
                    @else
                    N/A
                    @endif
                </h1>

            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 gap-8 px-4 pt-4 pb-32 lg:px-40 lg:grid-cols-2">
        <div>
            <h1 class="text-2xl font-bold text-left">Profile Information</h1>
            <div class="my-4 space-y-2 md:ml-12">
                <div class="flex justify-between">
                    <h1 class="font-semibold ">Email:</h1>
                    <p class=""> {{ Auth::user()->email }}</p>
                </div>
                <div class="flex justify-between">
                    <h1 class="font-semibold ">Distributor Type:</h1>
                    <p>
                        @if(Auth::user()->distributor)
                        {{ Auth::user()->distributor->distributor_type }}
                        @else
                        N/A
                        @endif
                    </p>

                </div>
                <div class="flex justify-between">
                    <h1 class="font-semibold ">Contact Number:</h1>
                    <p class="">{{ optional(Auth::user()->distributor)->contact_number ?? 'N/A' }}</p>

                </div>
                <div class="flex justify-between">
                    <h1 class="font-semibold ">Membership Status:</h1>
                    <p class="">{{ Auth::user()->status }}</p>
                </div>
                <div class="flex justify-between">
                    <h1 class="font-semibold ">Join Date:</h1>
                    <p class="">{{ Auth::user()->created_at->format('F j, Y') }}</p>
                </div>
            </div>
        </div>
        <div>
            <h1 class="text-2xl font-bold">Social media</h1>
            <div class="flex space-x-4">
                <a href="{{  (Auth::user()->socialMediaAccounts->facebook ?? '#') }}" target="_blank">
                    <img src="{{asset('images/icon-facebook.png')}}" alt="">
                </a>

                <a href="{{  (Auth::user()->socialMediaAccounts->tiktok ?? '#') }}" target="_blank">
                    <img src="{{asset('images/icon-tiktok.png')}}" alt="">
                </a>

                <a href="{{  (Auth::user()->socialMediaAccounts->instagram ?? '#') }}" target="_blank">
                    <img src="{{asset('images/icon-instagram.png')}}" alt="">
                </a>
            </div>
        </div>
    </div>

</div>

@endsection
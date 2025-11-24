@extends('layouts.main-app')

@section('title', 'Member')

@section('content')

@include('layouts.navbar')
<div class="relative h-[20rem]">
    <img class="absolute z-0 object-cover w-full h-full " src="{{asset('images/All-Banner.png')}}" alt="">
    <div class="container relative max-w-screen-xl py-32 mx-auto">
        <div class="flex flex-col items-center justify-center space-y-4">
            <h1 class="text-5xl font-semibold">Be Our Member</h1>
            <h1 class="text-xl">Home >> <span class="text-[#f56e98]">Be Our Member</span> </h1>
        </div>
    </div>
</div>



<div class="relative w-full h-full" data-aos="fade-up" data-aos-duration="1500">
    <div class="flex items-center justify-center w-full mb-40">
        <img class="absolute object-contain lg:object-cover z-10 w-[60rem] h-[15rem] mt-[15rem]" src="{{asset('images/Membership.png')}}" alt="">
    </div>

    <img class="absolute z-0 w-full h-full " src="{{asset('images/be_our_member/Background.png')}}" alt="">
    <div class="container relative max-w-screen-xl mx-auto ">
        <div class="grid items-center grid-cols-1 px-4 md:grid-cols-2">
            <div>
                <h1 class="text-2xl font-bold">Becoming a Member is Easy!</h1>
                <br>
                <br>
                <p>
                    Simpy create a free acount on our website and unlock all the amazing benefits mentioned above. It's that simple!
                    <br>
                    <br>
                    By joining the SY Glow membership program, you'll not only save money on your favorite skincare products, but also gain access to a wealth of exclusive benefits and a supportive community. Sign up today and unlock your journey to a more radiant you!
                </p>
                <div class="flex flex-col items-center my-8 space-x-0 space-y-4 lg:space-x-4 lg:space-y-0 lg:flex-row">
                    <a href="{{url('/be_our_member_distributors')}}">
                        <button class="bg-gradient-to-r from-[#f590b0] to to-[#f56e98] rounded-lg w-60 py-2 text-white border-2 border-white">SIGN UP NOW</button>
                    </a>
                    <a href="{{url('/login')}}">
                        <button class="bg-gradient-to-r from-[#f590b0] to to-[#f56e98] rounded-lg w-60 py-2 text-white border-2 border-white">ALREADY A MEMBER?</button>
                    </a>
                </div>
            </div>
            <div>
                <img src="{{asset('images/billboards/billboard_1.jpg')}}" alt="">
            </div>
        </div>
    </div>
</div>
<div class="flex flex-col items-center justify-center py-40 space-y-8 text-center" data-aos="fade-up" data-aos-duration="1500">
    <h1 class="text-5xl ">EXPLORE OUR MEMBERS NETWORK</h1>
    <div>
        <a href="{{url('/be_our_member_sign_up')}}">
            <button class="bg-gradient-to-r from-[#f590b0] to to-[#f56e98] rounded-lg px-8 md:px-40 py-2 text-white border-2 border-white">VIEW MEMBERS</button>
        </a>
    </div>
</div>

@include('layouts.member')
@include('layouts.footer')
@endsection
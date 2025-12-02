@extends('layouts.sidebar')

@section('title', 'Home')

@section('content')

<div class="flex flex-col justify-between p-8 text-4xl md:flex-row">
    <h1>ID</h1>
    <h1 class="text-4xl font-semibold">
        Welcome,
        @if(Auth::user()->distributor)
        {{ Auth::user()->distributor->name }}!
        @else
        {{ Auth::user()->name }}!
        @endif
    </h1>
</div>

<div class="flex flex-col gap-4 px-4 md:p-20 md:flex-row">
    <div class="w-full md:w-[20rem] h-full download-target">
        <div class="relative shadow-lg">
            <img class="absolute right-2 top-4 z-10 object-cover w-[4rem] h-[4rem]" src="{{asset('images/logo-footer.png')}}" alt="">
            <img class="absolute z-0 object-cover w-full h-full" src="{{asset('images/Distributor-Card.png')}}" alt="">
            <div class="relative z-50 flex flex-col items-center justify-center py-12">
                <img class="rounded-full h-[6rem] object-cover w-[6rem]" src="{{ asset('uploads/profile_pictures/' . (Auth::user()->distributor->profile_picture ?? 'default-avatar.jpg')) }}" alt="">
                <p> {{ Auth::user()->id }}</p>
                <p>
                    @if(Auth::user()->distributor)
                    @switch(Auth::user()->distributor->distributor_type)
                    @case(1)
                    Regional Distributor
                    @break
                    @case(2)
                    Provincial Distributor
                    @break
                    @case(3)
                    City Distributor
                    @break
                    @case(4)
                    Reseller Distributor
                    @break
                    @default
                    N/A
                    @endswitch
                    @else
                    N/A
                    @endif
                </p>
                <p class="text-[#f56e98] text-2xl"> {{ optional(Auth::user()->distributor)->name ?? Auth::user()->name }}</p>

                <div class="flex items-center justify-center py-4 space-x-2">
                    <div><img src="{{asset('images/icon-facebook.png')}}" alt=""></div>
                    <div><img src="{{asset('images/icon-tiktok.png')}}" alt=""></div>
                    <div><img src="{{asset('images/icon-instagram.png')}}" alt=""></div>
                </div>
                <ul class="space-y-2">
                    <li class="flex items-center space-x-4">
                        <img src="{{ asset('images/phone-call.png') }}" alt="Phone">
                        <p class="">{{ optional(Auth::user()->distributor)->contact_number ?? 'N/A' }}</p>
                    </li>
                    <li class="flex items-center space-x-4">
                        <img src="{{asset('images/route.png')}}" alt="">
                        <p>{{ optional(Auth::user()->distributor)->city ?? 'N/A' }}</p>

                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div>
        <div class="flex items-center">
            <button class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-xl w-20 py-2 text-white border-2 border-white download-button">
                <i class='text-4xl bx bx-download'></i>
            </button>
            <h1 class="text-2xl">Download</h1>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const downloadButton = document.querySelector('.download-button');
        const targetDiv = document.querySelector('.download-target');

        downloadButton.addEventListener('click', function() {
            html2canvas(targetDiv, {
                scale: 2, // Increase scale for better quality
                useCORS: true // Handle cross-origin images
            }).then(canvas => {
                const link = document.createElement('a');
                link.href = canvas.toDataURL('image/png');
                link.download = 'download.png'; // Name of the downloaded image file
                link.click();
            });
        });
    });
</script>



@endsection
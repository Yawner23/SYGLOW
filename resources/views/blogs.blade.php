@extends('layouts.main-app')

@section('title', 'Blogs')

@section('content')

@include('layouts.navbar')

<div class="relative h-[20rem]">
    <img class="absolute z-0 object-cover w-full h-full" src="{{asset('images/All-Banner.png')}}" alt="">
    <div class="container relative max-w-screen-xl py-32 mx-auto">
        <div class="flex flex-col items-center justify-center space-y-4">
            <h1 class="text-5xl font-semibold">Blogs</h1>
            <h1 class="text-xl">Home >> <span class="text-[#f56e98]">Blogs</span> </h1>
        </div>
    </div>
</div>

<div class="relative w-full h-full">
    <img class="absolute z-0 object-cover w-full h-full" src="{{asset('images/Golden-Sparkling-Wave.png')}}" alt="">
    <div class="container relative max-w-screen-xl mx-auto">

        <div class="grid grid-cols-1 px-4 pt-40 overflow-hidden md:gap-8 md:grid-cols-3">
            <div class="col-span-2 py-8" data-aos="fade-right" data-aos-duration="1500">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                  @foreach($blogs as $blog)
                    <a href="{{ url('blogs_details', $blog->id) }}">
                        <div class="shadow-md" data-aos="zoom-in" data-aos-duration="1500">
                            <div>
                                @if ($blog->image && !$blog->video_link)
                                    <img class="object-cover w-full h-[20rem]" src="{{ asset($blog->image) }}" alt="{{ $blog->title }}">
                                @elseif ($blog->video_link && !$blog->image)
                                    @php
                                    $isYouTube = preg_match('/youtube\.com\/watch\?v=|youtu\.be\//', $blog->video_link);
                                    $isFacebook = preg_match('/facebook\.com\/.*\/videos\//', $blog->video_link);
                                    
                                    if ($isYouTube) {
                                        $embedUrl = str_replace(
                                            ['youtube.com/watch?v=', 'youtu.be/'],
                                            ['youtube.com/embed/', 'youtube.com/embed/'],
                                            $blog->video_link
                                        );
                                    } elseif ($isFacebook) {
                                        $embedUrl = str_replace(
                                            ['facebook.com/'],
                                            ['facebook.com/plugins/video.php?href=https://www.facebook.com/'],
                                            $blog->video_link
                                        );
                                    } else {
                                        $embedUrl = $blog->video_link;
                                    }
                                    @endphp
                                    <iframe class="w-full h-[20rem]" src="{{ $embedUrl }}" frameborder="0" allowfullscreen></iframe>
                                @endif
                            </div>
                            <div class="p-4 space-y-4 bg-white">
                                <h1>{{ $blog->user->name }} - {{ $blog->created_at->diffForHumans() }}</h1>
                                <h1 class="font-bold">{{ $blog->title }}</h1>
                                <p>{{ $blog->short_description }}</p>
                            </div>
                        </div>
                    </a>
                    @endforeach


                </div>


            </div>
            <div data-aos="fade-left" data-aos-duration="1500">
                <div class="py-8">
                    <form id="filter-form" method="GET" action="{{ route('blogs') }}">
                        <section class="flex items-center gap-4 py-4">
                            <h1 class="font-semibold">Search</h1>
                            <input class="w-full h-full p-2" type="text" name="search" value="{{ request()->input('search') }}">
                        </section>
                        <section>
                            <div class="bg-[#f56e98] text-white p-2">
                                Category
                            </div>
                            <img class="my-4" src="{{asset('images/wishlist-line.png')}}" alt="">
                            @foreach ($categories as $category)
                            <div class="flex items-center justify-between">
                                <label for="category{{ $category->id }}">{{ $category->category_name }}</label>
                                <input type="checkbox" value="{{ $category->id }}" id="category{{ $category->id }}" name="category[]"
                                    {{ in_array($category->id, request()->input('category', [])) ? 'checked' : '' }}>
                            </div>
                            @endforeach

                        </section>

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

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the form and category checkboxes
        const form = document.getElementById('filter-form');
        const categoryCheckboxes = form.querySelectorAll('input[name="category[]"]');

        // Attach change event listeners to checkboxes
        categoryCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                form.submit(); // Submit the form when a checkbox is changed
            });
        });
    });
</script>


@include('layouts.member')
@include('layouts.footer')

@endsection
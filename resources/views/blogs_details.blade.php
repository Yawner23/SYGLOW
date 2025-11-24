@extends('layouts.main-app')

@section('title', 'Blogs')

@section('content')

@include('layouts.navbar')

<div class="relative h-[20rem]">
    <img class="absolute z-0 object-cover w-full h-full " src="{{asset('images/All-Banner.png')}}" alt="">
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
        <div class="grid grid-cols-1 px-4 pt-12 md:gap-8 md:grid-cols-3">
            <div class="col-span-2 py-8">
              <section class="space-y-8">
                    @if ($blogs->video_link && !$blogs->image)
                        @php
                        $isYouTube = preg_match('/youtube\.com\/watch\?v=|youtu\.be\//', $blogs->video_link);
                        $isFacebook = preg_match('/facebook\.com\/.*\/videos\//', $blogs->video_link);
                
                        if ($isYouTube) {
                            $embedUrl = str_replace(
                                ['youtube.com/watch?v=', 'youtu.be/'],
                                ['youtube.com/embed/', 'youtube.com/embed/'],
                                $blogs->video_link
                            );
                        } elseif ($isFacebook) {
                            $embedUrl = str_replace(
                                ['facebook.com/'],
                                ['facebook.com/plugins/video.php?href=https://www.facebook.com/'],
                                $blogs->video_link
                            );
                        } else {
                            $embedUrl = $blogs->video_link;
                        }
                        @endphp
                        <iframe class="object-cover w-full h-[30rem] shadow-xl" src="{{ $embedUrl }}" frameborder="0" allowfullscreen></iframe>
                    @elseif ($blogs->image && !$blogs->video_link)
                        <img class="object-cover w-full h-full shadow-xl" src="{{ asset($blogs->image) }}" alt="{{ $blogs->title }}">
                    @endif
                
                    <h1 class="text-4xl font-bold">
                        {{ $blogs->title }}
                    </h1>
                    <p>
                        {!! $blogs->description !!}
                    </p>
                </section>


                <section class="p-4 my-8 bg-white shadow-lg">
                    <div class="flex flex-col justify-between space-y-4 md:space-y-0 md:flex-row">
                        <div class="flex items-center space-x-4 md:space-x-10">
                            <img class="object-cover rounded-full w-14 h-14" src="{{ asset('uploads/profile_pictures/' . ($blogs->user->profile_picture ?? 'default-avatar.jpg')) }}" alt="">
                            <div>
                                <h1>{{$blogs->user->name}}</h1>
                                <p>{{ $blogs->created_at->format('F j, Y') }} | {{ $blogs->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-center space-x-2 text-3xl">
                            <button>
                                <i class='bx bxl-instagram-alt bx-rotate-180'></i>
                            </button>
                            <button>
                                <i class='bx bxl-facebook-circle'></i>
                            </button>
                            <button id="share_button">
                                <i class='bx bx-share-alt'></i>
                            </button>
                        </div>
                    </div>
                </section>
                <section>
                    <h1 class="py-4 text-xl font-semibold">
                        Recent Posts
                    </h1>
                    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                        @foreach($recentBlogs as $recentBlog)
                        <a href="{{url('blogs_details', $recentBlog->id)}}">
                            <div class="shadow-lg ">
                                <div>
                                    @if ($recentBlog->image && !$recentBlog->video_link)
                                    <img class="object-cover w-full h-[20rem]" src="{{asset($recentBlog->image)}}" alt="">
                                    @elseif ($recentBlog->video_link && !$recentBlog->image)
                                    @php
                                    $isYouTube = preg_match('/youtube\.com\/watch\?v=|youtu\.be\//', $recentBlog->video_link);
                                    $embedUrl = $isYouTube ? str_replace(
                                    ['youtube.com/watch?v=', 'youtu.be/'],
                                    ['youtube.com/embed/', 'youtube.com/embed/'],
                                    $recentBlog->video_link
                                    ) : $recentBlog->video_link;
                                    @endphp
                                    <iframe class="w-full h-[20rem]" src="{{ $embedUrl }}" frameborder="0" allowfullscreen></iframe>
                                    @endif

                                </div>
                                <div class="p-4 space-y-4 bg-white">
                                    <h1>Admin {{ $recentBlog->created_at->diffForHumans() }}</h1>
                                    <h1 class="font-bold">{{ $recentBlog->title }}</h1>
                                    <p>{{ $recentBlog->short_description }}</p>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </section>
            </div>

            <div>
                <div class="py-8">
                    <section>
                        <div class="bg-[#f56e98] text-white p-2">
                            Category
                        </div>
                        <img class="my-4" src="{{asset('images/wishlist-line.png')}}" alt="">
                        @foreach ($category as $categories)
                        <div class="flex items-center justify-between">
                            <label for="category_{{$categories->id}}">{{$categories->category_name}}</label>
                            <input disabled type="checkbox" id="category_{{$categories->id}}" name="category[]"
                                value="{{$categories->id}}"
                                {{ $categories->id == $blogs->category_id ? 'checked' : '' }}>
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
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('share_button').addEventListener('click', function() {
        var blogUrl = "{{ url('blogs_details', $blogs->id) }}";
        var facebookShareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(blogUrl)}`;

        var shareWindow = window.open(facebookShareUrl, '_blank', 'width=600,height=400');
    });
</script>

@include('layouts.member')
@include('layouts.footer')


@endsection
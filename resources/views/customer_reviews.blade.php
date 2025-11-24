@extends('layouts.sidebar')

@section('title', 'Home')

@section('content')

<div class="flex flex-col justify-between p-8 md:flex-row">
    <h1 class="text-4xl font-semibold">Ordered Items</h1>
    <h1 class="text-4xl font-semibold">Welcome, {{ Auth::user()->customer->first_name }}!</h1>
</div>
<div class="flex flex-col justify-between p-8 space-y-4 lg:flex-row lg:space-y-0">
    <div class="flex items-center space-x-8">
        <img class="rounded-full h-[6rem] object-cover w-[6rem]" src="{{ asset('uploads/profile_pictures/' . (Auth::user()->customer->profile_picture ?? 'default-avatar.jpg')) }}" alt="">
        <div>
            <h1 class="text-[#f590b0] text-4xl">{{ Auth::user()->customer->first_name }} {{ Auth::user()->customer->last_name }}</h1>
            <p class="text-xl">{{ Auth::user()->id }}</p>
            <p class="text-xl">{{ Auth::user()->email }}</p>
        </div>
    </div>
</div>
<form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="text" value="{{ $payment->id }}" name="payment_id" id="payment_id" hidden readonly>
    <div class="p-8 border-2 rounded-xl">
        <div class="flex items-center">
            <div>
                @foreach ($payment->products as $product)
                @php
                $productImage = $product->product->images->first(); // Get the first image for the product
                @endphp
                @if ($productImage)
                <img class="bg-[#fdebdd] object-contain px-8 py-4 h-[8rem] w-full" src="{{ asset('images/uploads/product_images/' . $productImage->image_path) }}" alt="{{ $product->product->product_name }}">
                @else
                <p>No image available</p>
                @endif
                @endforeach
            </div>
            <div class="grid grid-cols-2 bg-[#feecea] w-full p-4 gap-4 rounded-r-xl text-lg">
                <div>
                    <h1>Bundle: #{{ $payment->id }}</h1>
                    <h1>Total Amount: {{ $payment->total }}</h1>
                </div>
                <div>
                    @foreach ($payment->products as $product)
                    <h1>Items: {{ $product->product->product_name }} {{ $product->quantity }}x</h1>
                    @endforeach
                    <h1>Status: {{ $payment->status }}</h1>
                </div>
            </div>
        </div>


        @if (!$existingReview)
        <div class="flex items-center mt-4 space-x-4">
            <h1>Ratings:</h1>
            <div class="flex space-x-1">
                @for ($i = 1; $i <= 5; $i++)
                    <label>
                    <input type="radio" name="rating" value="{{ $i }}" class="hidden" {{ $i == old('rating', 5) ? 'checked' : '' }} onclick="updateStars(this.value)">
                    <i id="star-{{ $i }}" class='bx bxs-star text-3xl {{ $i <= old('rating', 0) ? "text-yellow-500" : "text-gray-100" }}'></i>
                    </label>
                    @endfor
                    <script>
                        function updateStars(selectedRating) {
                            for (let i = 1; i <= 5; i++) {
                                let star = document.getElementById('star-' + i);
                                if (i <= selectedRating) {
                                    star.classList.add('text-yellow-500');
                                    star.classList.remove('text-gray-300');
                                } else {
                                    star.classList.add('text-gray-300');
                                    star.classList.remove('text-yellow-500');
                                }
                            }
                        }
                    </script>
            </div>
        </div>
        <div class="mt-4">
            <h1><span class="text-[#f590b0]">*</span>Attach Comment</h1>
            <textarea class="w-full p-4 bg-gray-100 border-transparent " name="comment" id="comment" rows="8">{{ old('comment') }}</textarea>
        </div>
        <div class="flex flex-row items-center space-x-4">
            <label class="flex items-center justify-center space-x-4 font-bold cursor-pointer" for="image">
                <span>
                    <i class='p-2 text-5xl text-gray-400 bg-white border-2 bx bxs-image'></i>
                </span>
                <span class="text-[#f56e98] ">*</span>
                Image
            </label>
            <input type="file" id="image" name="image" hidden accept="image/*">
            <!-- Changed the id of this div to 'imagePreview' -->
            <div id="imagePreview" class="mt-2"></div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                function previewImage(input, previewId) {
                    const preview = document.getElementById(previewId);
                    const file = input.files[0];

                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="object-cover w-32 h-32 border-2 border-gray-300 rounded">`;
                        };

                        reader.readAsDataURL(file);
                    } else {
                        preview.innerHTML = '';
                    }
                }

                document.getElementById('image').addEventListener('change', function() {
                    previewImage(this, 'imagePreview'); // Update the preview div ID here
                });
            });
        </script>

        <div class="flex col-span-2 py-4">
            <button type="submit" class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg w-40 py-2 text-white border-2 border-white">Submit</button>
        </div>
        @endif
    </div>
</form>

@endsection
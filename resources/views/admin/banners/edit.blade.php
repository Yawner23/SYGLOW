<x-app-layout>
    <div class="container max-w-lg p-6 mx-auto bg-white rounded-lg shadow-lg">
        <h1 class="mb-6 text-3xl font-extrabold text-gray-800">Edit Banner</h1>
        <form action="{{ route('banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" id="title" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg " value="{{ $banner->title }}" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <input type="text" name="description" id="description" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg " value="{{ $banner->description }}" required>
            </div>
            <div class="mb-4">
                <label for="banner_image" class="block text-sm font-medium text-gray-700">Banner Image : Image Size (1920x946)</label>
                <input type="file" name="banner_image" id="banner_image" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600">
                @if($banner->banner_image)
                <img src="{{ asset($banner->banner_image) }}" alt="Current Banner Image" class="object-cover w-32 h-32 mt-2 border border-gray-300 rounded-lg">
                @endif
            </div>
            <button type="submit" class="px-6 py-3 text-white transition bg-gradient-to-r from-[#f590b0] to to-[#f56e98] rounded-lg ">Update Banner</button>
        </form>
    </div>
</x-app-layout>
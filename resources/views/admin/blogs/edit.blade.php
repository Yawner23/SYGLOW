<x-app-layout>
    <div class="container max-w-lg p-6 mx-auto bg-white rounded-lg shadow-lg">
        <h1 class="mb-6 text-3xl font-extrabold text-gray-800">Edit Blogs</h1>
        <form action="{{ route('blogs.update', $blogs->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="text" name="user_id" id="user_id" value="{{ Auth::user()->id }}" class="hidden ">

            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" id="title" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg " value="{{ old('title', $blogs->title) }}" required>
                @error('title')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="short_description" class="block text-sm font-medium text-gray-700">Short Description</label>
                <textarea name="short_description" id="short_description" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg " rows="4" required>{{ old('short_description', $blogs->short_description) }}</textarea>
                @error('short_description')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg " required>{{ old('description', $blogs->description) }}</textarea>
                @error('description')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
            <script>
                CKEDITOR.replace('description');
            </script>

            <div class="mb-4">
                <label for="video_link" class="block text-sm font-medium text-gray-700">Video Link</label>
                <input type="url" name="video_link" id="video_link" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg " value="{{ old('video_link', $blogs->video_link) }}" required>
                @error('video_link')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700">Image (Leave blank to keep current)</label>
                <input type="file" name="image" id="image" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600">
                @if($blogs->image)
                <img src="{{ asset($blogs->image) }}" alt="Current Banner Image" class="object-cover w-32 h-32 mt-2 border border-gray-300 rounded-lg">
                @endif
                @error('image')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="category_id" class="block text-sm font-medium text-gray-700">Product Type</label>
                <select id="category_id" name="category_id" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg">
                    <option value="">Select Category</option>
                    @foreach($category as $categories)
                    <option value="{{ $categories->id }}" {{ old('category_id', $blogs->category_id) == $categories->id ? 'selected' : '' }}>
                        {{ $categories->category_name }}
                    </option>
                    @endforeach
                </select>
                @error('category_id')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="px-6 py-3 text-white transition bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg">Update Blogs</button>
        </form>
    </div>

    <script>
        const videoLinkInput = document.getElementById('video_link');
        const imageInput = document.getElementById('image');

        function toggleInputState() {
            if (videoLinkInput.value.trim() !== '') {
                imageInput.disabled = true;
                imageInput.value = ''; // Clear the image input if video link is filled
            } else {
                imageInput.disabled = false;
            }

            if (imageInput.value.trim() !== '') {
                videoLinkInput.disabled = true;
                videoLinkInput.value = ''; // Clear the video link input if image is filled
            } else {
                videoLinkInput.disabled = false;
            }
        }

        videoLinkInput.addEventListener('input', toggleInputState);
        imageInput.addEventListener('input', toggleInputState);

        // Initialize the state on page load
        toggleInputState();
    </script>
</x-app-layout>

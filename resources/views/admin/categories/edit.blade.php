<x-app-layout>
    <div class="container max-w-lg p-6 mx-auto bg-white rounded-lg shadow-lg">
        <h1 class="mb-6 text-3xl font-extrabold text-gray-800">Edit Categories</h1>
        <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="category_name" class="block text-sm font-medium text-gray-700">Category Name</label>
                <input type="text" name="category_name" id="category_name" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg " value="{{ $category->category_name }}" required>
            </div>
            <button type="submit" class="px-6 py-3 text-white transition bg-gradient-to-r from-[#f590b0] to to-[#f56e98] rounded-lg ">Update Categories</button>
        </form>
    </div>
</x-app-layout>
<x-app-layout>
    <div class="container max-w-lg p-6 mx-auto bg-white rounded-lg shadow-lg">
        <h1 class="mb-6 text-3xl font-extrabold text-gray-800">Create Category</h1>
        <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="mb-4">
                <label for="category_name" class="block text-sm font-medium text-gray-700">Category Name</label>
                <input type="text" name="category_name" id="category_name" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg " required>
            </div>

            <button type="submit" class="px-6 py-3 text-white transition  rounded-lg bg-gradient-to-r from-[#f590b0] to to-[#f56e98]">Create Category</button>
        </form>
    </div>
</x-app-layout>
<x-app-layout>
    <div class="container max-w-lg p-6 mx-auto bg-white rounded-lg shadow-lg">
        <h1 class="mb-6 text-3xl font-extrabold text-gray-800">Create Product Type</h1>
        <form action="{{ route('product_type.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="mb-4">
                <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                <select id="category_id" name="category_id" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg">
                    <option value="">Select Category</option>
                    @foreach($category as $categories)
                    <option value=" {{ $categories->id }}" {{ old('category_id') == $categories->id ? 'selected' : '' }}>{{ $categories->category_name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="product_type" class="block text-sm font-medium text-gray-700">Product Type</label>
                <input type="text" name="product_type" id="product_type" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg " required>
            </div>

            <button type="submit" class="px-6 py-3 text-white transition  rounded-lg bg-gradient-to-r from-[#f590b0] to to-[#f56e98]">Create Partners</button>
        </form>
    </div>
</x-app-layout>
<x-app-layout>
    <div class="container max-w-lg p-6 mx-auto bg-white rounded-lg shadow-lg">
        <h1 class="mb-6 text-3xl font-extrabold text-gray-800">Edit Product Type</h1>
        <form action="{{ route('product_type.update', $product_type->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                <select id="category_id" name="category_id" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg focus:border-red-600 focus:ring-1 focus:ring-red-600">
                    <option value="">Select Category</option>
                    @foreach($category as $categories)
                    <option value="{{ $categories->id }}" {{ $product_type->category_id == $categories->id ? 'selected' : '' }}>{{ $categories->category_name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="product_type" class="block text-sm font-medium text-gray-700">Product Type</label>
                <input value="{{$product_type->product_type }}" type="text" name="product_type" id="product_type" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg " required>
            </div>

            <button type="submit" class="px-6 py-3 text-white transition bg-gradient-to-r from-[#f590b0] to to-[#f56e98] rounded-lg ">Update Product Type</button>
        </form>
    </div>
</x-app-layout>
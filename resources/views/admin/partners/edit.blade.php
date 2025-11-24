<x-app-layout>
    <div class="container max-w-lg p-6 mx-auto bg-white rounded-lg shadow-lg">
        <h1 class="mb-6 text-3xl font-extrabold text-gray-800">Edit Partners</h1>
        <form action="{{ route('partners.update', $partners->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="partners_image" class="block text-sm font-medium text-gray-700">Partners Image : Image Size (209x64) (Leave blank to keep current)</label>
                <input type="file" name="partners_image" id="partners_image" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600">
                @if($partners->partners_image)
                <img src="{{ asset($partners->partners_image) }}" alt="Current Partners Image" class="object-cover w-32 h-32 mt-2 border border-gray-300 rounded-lg">
                @endif
            </div>
            <button type="submit" class="px-6 py-3 text-white transition bg-gradient-to-r from-[#f590b0] to to-[#f56e98] rounded-lg ">Update Partners</button>
        </form>
    </div>
</x-app-layout>
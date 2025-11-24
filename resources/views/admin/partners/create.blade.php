<x-app-layout>
    <div class="container max-w-lg p-6 mx-auto bg-white rounded-lg shadow-lg">
        <h1 class="mb-6 text-3xl font-extrabold text-gray-800">Create Partners</h1>
        <form action="{{ route('partners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="mb-4">
                <label for="partners_image" class="block text-sm font-medium text-gray-700">Partners Image : Image Size (209x64)</label>
                <input type="file" name="partners_image" id="partners_image" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600" required>
            </div>
            <button type="submit" class="px-6 py-3 text-white transition  rounded-lg bg-gradient-to-r from-[#f590b0] to to-[#f56e98]">Create Partners</button>
        </form>
    </div>
</x-app-layout>
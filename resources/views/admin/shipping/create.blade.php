<x-app-layout>
    <div class="container max-w-screen-sm p-6 mx-auto bg-white rounded-lg shadow-md">
        <h1 class="mb-6 text-3xl font-bold text-center">Add Shipping Fee</h1>
        <form action="{{ route('shipping.store', $payment) }}" method="POST" class="space-y-4">
            @csrf
            <div class="mb-4">
                <label for="courier" class="block text-sm font-medium text-gray-700">Courier</label>
                <input type="text" name="courier" id="courier" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50" value="{{ old('courier', $shipping->courier ?? '') }}">
                @error('courier')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="shipping_fee" class="block text-sm font-medium text-gray-700">Shipping Fee</label>
                <input type="number" name="shipping_fee" id="shipping_fee" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50" value="{{ old('shipping_fee', $shipping->shipping_fee ?? '') }}">
                @error('shipping_fee')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div class="text-center">
                <button type="submit" class="px-6 py-3 text-white transition rounded-lg bg-gradient-to-r from-[#f590b0] to-[#f56e98]">Add Shipping Fee</button>
            </div>
        </form>
    </div>
</x-app-layout>
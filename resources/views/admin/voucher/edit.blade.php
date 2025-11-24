<x-app-layout>
    <div class="container max-w-lg p-6 mx-auto bg-white rounded-lg shadow-lg">
        <h1 class="mb-6 text-3xl font-extrabold text-gray-800">Edit Voucher</h1>

        @if ($errors->any())
        <div class="p-4 mb-4 text-white bg-red-500 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('voucher.update', $voucher->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="code" class="block text-sm font-medium text-gray-700">Code</label>
                <input type="text" name="code" id="code" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg" value="{{ old('code', $voucher->code) }}" required>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="4" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg">{{ old('description', $voucher->description) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="discount_type" class="block text-sm font-medium text-gray-700">Discount Type</label>
                <select name="discount_type" id="discount_type" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg" required>
                    <option value="percentage" {{ old('discount_type', $voucher->discount_type) == 'percentage' ? 'selected' : '' }}>Percentage</option>
                    <option value="fixed_amount" {{ old('discount_type', $voucher->discount_type) == 'fixed_amount' ? 'selected' : '' }}>Fixed Amount</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="discount_value" class="block text-sm font-medium text-gray-700">Discount Value</label>
                <input type="number" name="discount_value" id="discount_value" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg" value="{{ old('discount_value', $voucher->discount_value) }}" required>
            </div>

            <div class="mb-4">
                <label for="usage_limit" class="block text-sm font-medium text-gray-700">Usage Limit</label>
                <input type="number" name="usage_limit" id="usage_limit" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg" value="{{ old('usage_limit', $voucher->usage_limit) }}">
            </div>

            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg" required>
                    <option value="active" {{ old('status', $voucher->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $voucher->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="expired" {{ old('status', $voucher->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>

            <button type="submit" class="px-6 py-3 text-white transition rounded-lg bg-gradient-to-r from-[#f590b0] to-[#f56e98]">Update Voucher</button>
        </form>
    </div>
</x-app-layout>
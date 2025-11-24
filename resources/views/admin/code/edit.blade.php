<x-app-layout>
    <div class="container p-4 mx-auto">
        <h1 class="mb-4 text-2xl font-bold">Edit Referral Code</h1>
        <form action="{{ route('code.update', $referralCode->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="referral_code" class="block text-sm font-medium text-gray-700">Referral Code</label>
                <input type="number" name="referral_code" id="referral_code" class="block w-full mt-1" value="{{ old('referral_code', $referralCode->referral_code) }}" required>
                @error('referral_code')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="px-4 py-2 text-white bg-gradient-to-r from-[#f590b0] to to-[#f56e98] rounded">Update</button>
        </form>
    </div>
</x-app-layout>
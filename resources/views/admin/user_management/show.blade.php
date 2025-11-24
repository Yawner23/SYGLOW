<x-app-layout>
    <div class="container max-w-screen-md py-8 mx-auto">
        <h1 class="mb-6 text-3xl font-bold text-gray-800">User Details</h1>
        <div class="p-8 bg-white rounded-lg shadow-lg">
            <div class="mb-4">
                <p class="text-lg font-medium text-gray-700"><strong>ID:</strong> {{ $user->id }}</p>
                <p class="text-lg font-medium text-gray-700"><strong>Email:</strong> {{ $user->email }}</p>
                <p class="text-lg font-medium text-gray-700"><strong>Status:</strong> {{ $user->status }}</p>
            </div>

            @if($user->customer)
            <div class="mt-6">
                <h2 class="text-2xl font-semibold text-gray-800">Customer Details</h2>
                <div class="flex items-center mt-4">
                    <img class="object-cover mr-4 rounded-full w-28 h-28" src="{{ asset('uploads/profile_pictures/' . ($user->customer->profile_picture ?? 'default-avatar.jpg')) }}" alt="Profile Picture">
                    <div>
                        <p class="text-lg font-medium text-gray-700"><strong>Customer ID:</strong> {{ $user->customer->id }}</p>
                        <p class="text-lg font-medium text-gray-700"><strong>Name:</strong> {{ $user->customer->first_name }} {{ $user->customer->last_name }}</p>
                        <p class="text-lg font-medium text-gray-700"><strong>Contact Number:</strong> {{ $user->customer->contact_number }}</p>
                        <p class="text-lg font-medium text-gray-700"><strong>Date of Birth:</strong> {{ $user->customer->date_of_birth }}</p>
                        <p class="text-lg font-medium text-gray-700"><strong>Referral Code:</strong> {{ $user->customer->referral_code }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if($user->socialMediaAccounts)
            <div class="mt-6">
                <h2 class="text-2xl font-semibold text-gray-800">Social Media Accounts</h2>
                <div class="mt-4">
                    <p class="text-lg font-medium text-gray-700"><strong>Facebook:</strong> {{ $user->socialMediaAccounts->facebook }}</p>
                    <p class="text-lg font-medium text-gray-700"><strong>TikTok:</strong> {{ $user->socialMediaAccounts->tiktok }}</p>
                    <p class="text-lg font-medium text-gray-700"><strong>Instagram:</strong> {{ $user->socialMediaAccounts->instagram }}</p>
                </div>
            </div>
            @endif

            @if($user->distributor)
            <div class="mt-6">
                <h2 class="text-2xl font-semibold text-gray-800">Distributor Details</h2>
                <div class="mt-4">
                    <p class="text-lg font-medium text-gray-700"><strong>Distributor ID:</strong> {{ $user->distributor->id }}</p>
                    <p class="text-lg font-medium text-gray-700"><strong>Name:</strong> {{ $user->distributor->name }}</p>
                    <p class="text-lg font-medium text-gray-700"><strong>Type:</strong> {{ $user->distributor->distributor_type }}</p>
                    <p class="text-lg font-medium text-gray-700"><strong>Region:</strong> {{ $user->distributor->region }}</p>
                    <p class="text-lg font-medium text-gray-700"><strong>Province:</strong> {{ $user->distributor->province }}</p>
                    <p class="text-lg font-medium text-gray-700"><strong>City:</strong> {{ $user->distributor->city }}</p>
                    <p class="text-lg font-medium text-gray-700"><strong>Barangay:</strong> {{ $user->distributor->brgy }}</p>
                    <p class="text-lg font-medium text-gray-700"><strong>Referral Code:</strong> {{ $user->distributor->code }}</p>
                </div>

                <div class="mt-4">
                    <h3 class="text-lg font-medium text-gray-700">Verification Documents:</h3>
                    <div class="flex mt-2 space-x-4">
                        @if($user->distributor->valid_id_path)
                        <img class="object-cover w-32 h-32 border rounded-md shadow-sm" src="{{ asset($user->distributor->valid_id_path) }}" alt="Valid ID">
                        @else
                        <p class="text-gray-500">No Valid ID uploaded</p>
                        @endif

                        @if($user->distributor->photo_with_background_path)
                        <img class="object-cover w-32 h-32 border rounded-md shadow-sm" src="{{ asset($user->distributor->photo_with_background_path) }}" alt="Photo with Background">
                        @else
                        <p class="text-gray-500">No Photo with Background uploaded</p>
                        @endif

                        @if($user->distributor->selfie_with_id_path)
                        <img class="object-cover w-32 h-32 border rounded-md shadow-sm" src="{{ asset($user->distributor->selfie_with_id_path) }}" alt="Selfie with ID">
                        @else
                        <p class="text-gray-500">No Selfie with ID uploaded</p>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <div class="mt-8">
                <a href="{{ route('user_management.index') }}" class="inline-block px-6 py-3 text-lg font-semibold text-white bg-gray-700 rounded hover:bg-gray-800">
                    Back to List
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
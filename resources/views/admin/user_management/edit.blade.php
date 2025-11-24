<x-app-layout>
    <div class="container max-w-lg p-6 mx-auto bg-white rounded-lg shadow-lg">
        <h1 class="mb-6 text-3xl font-extrabold text-gray-800">Edit User</h1>

        @if ($errors->any())
        <div class="p-4 mb-4 text-white bg-red-500 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('user_management.update', $user->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg" value="{{ old('name', $user->name) }}" required>
                @error('name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg" value="{{ old('email', $user->email) }}" required>
                @error('email')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password (Leave blank to keep current password)</label>
                <input type="password" name="password" id="password" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg">
                @error('password')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg">
                @error('password_confirmation')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div class="mb-4">
                <label for="role_id" class="block text-sm font-medium text-gray-700">Role</label>
                <select name="role_id" id="role_id" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg" required>
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ $role->id == $user->roles->first()->id ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role_id')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="px-6 py-3 text-white transition rounded-lg bg-gradient-to-r from-[#f590b0] to-[#f56e98]">Update User</button>
        </form>
    </div>
</x-app-layout>
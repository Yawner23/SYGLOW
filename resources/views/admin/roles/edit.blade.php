<x-app-layout>
    <div class="container max-w-lg p-6 mx-auto bg-white rounded-lg shadow-lg">
        <h1 class="mb-6 text-3xl font-extrabold text-gray-800">Edit Role</h1>

        @if ($errors->any())
        <div class="p-4 mb-4 text-white bg-red-500 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('roles.update', $role->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Role Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Role Name</label>
                <input type="text" name="name" id="name" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg" value="{{ old('name', $role->name) }}" required>
                @error('name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Permissions -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Permissions</label>
                @foreach($permissions as $permission)
                <div class="flex items-center">
                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission{{ $permission->id }}"
                        {{ $role->permissions->contains($permission->id) ? 'checked' : '' }} class="mr-2">
                    <label for="permission{{ $permission->id }}" class="text-sm text-gray-600">{{ $permission->name }}</label>
                </div>
                @endforeach
            </div>

            <button type="submit" class="px-6 py-3 text-white transition rounded-lg bg-gradient-to-r from-[#f590b0] to-[#f56e98]">Update Role</button>
        </form>
    </div>
</x-app-layout>
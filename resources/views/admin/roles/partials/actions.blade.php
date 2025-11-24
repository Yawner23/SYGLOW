<div class="flex space-x-2">
    <a href="{{ route('roles.edit', $role->id) }}" class="px-4 py-2 text-white bg-blue-500 rounded">
        Edit
    </a>
    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="px-4 py-2 text-white bg-red-500 rounded">Delete</button>
    </form>
</div>
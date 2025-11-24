<div class="flex space-x-2">
    <a href="{{ route('user_management.show', $row->id) }}" class="text-xl text-black">
        <i class='bx bx-expand-alt'>View</i>
    </a>
    <a href="{{ route('user_management.edit', $row->id) }}" class="text-xl text-black">
        <i class='bx bx-edit'>Edit</i>
    </a>
    <form action="{{ route('user_management.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-xl text-red-500 "><i class='bx bx-trash'>Delete</i></button>
    </form>
</div>
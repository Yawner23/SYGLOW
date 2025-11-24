<x-app-layout>
    <div class="container max-w-lg p-6 mx-auto bg-white rounded-lg shadow-lg">
        <h1 class="mb-6 text-3xl font-extrabold text-gray-800">Create User</h1>

        @if ($errors->any())
        <div class="p-4 mb-4 text-white bg-red-500 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('user_management.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Role -->
            <div class="mb-4">
                <label for="role_id" class="block text-sm font-medium text-gray-700">Role</label>
                <select name="role_id" id="role_id" class="block w-full p-2 mt-1 border border-gray-300 rounded-lg" required>
                    <option value="">Select Role</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" class="block w-full p-2 mt-1 border border-gray-300 rounded-lg" value="{{ old('name') }}" required>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="block w-full p-2 mt-1 border border-gray-300 rounded-lg" value="{{ old('email') }}" required>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" class="block w-full p-2 mt-1 border border-gray-300 rounded-lg" required>
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="block w-full p-2 mt-1 border border-gray-300 rounded-lg" required>
            </div>


            <!-- Customer & Distributor Fields -->
            <div id="customer-fields" class="hidden space-y-4">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" name="first_name" id="first_name" class="block w-full p-2 mt-1 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="block w-full p-2 mt-1 border border-gray-300 rounded-lg">
                </div>
            </div>

            <div id="distributor-fields" class="hidden space-y-4">
                <div>
                    <label for="distributor_type" class="block text-sm font-medium text-gray-700">Distributor Type</label>
                    <select name="distributor_type" id="distributor_type" class="block w-full p-2 mt-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e98]">
                        <option value="">Select Distributor Type</option>
                        <option value="1">Regional Distributor</option>
                        <option value="2">Provincial Distributor</option>
                        <option value="3">City Distributor</option>
                        <option value="4">Reseller Distributor</option>
                    </select>
                </div>
                <div>
                    <label for="region" class="block text-sm font-medium text-gray-700">Region</label>
                    <input type="text" name="region" id="region" class="block w-full p-2 mt-1 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label for="province" class="block text-sm font-medium text-gray-700">Province</label>
                    <input type="text" name="province" id="province" class="block w-full p-2 mt-1 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                    <input type="text" name="city" id="city" class="block w-full p-2 mt-1 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label for="brgy" class="block text-sm font-medium text-gray-700">Barangay</label>
                    <input type="text" name="brgy" id="brgy" class="block w-full p-2 mt-1 border border-gray-300 rounded-lg">
                </div>
            </div>

            <!-- Single Contact Number field for both roles -->
            <div class="mb-4">
                <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                <input type="text" name="contact_number" id="contact_number" class="block w-full p-2 mt-1 border border-gray-300 rounded-lg">
            </div>

            <button type="submit" class="px-6 py-3 text-white transition rounded-lg bg-gradient-to-r from-[#f590b0] to-[#f56e98]">Create User</button>
        </form>
    </div>

    <script>
        const roleSelect = document.getElementById('role_id');
        const customerFields = document.getElementById('customer-fields');
        const distributorFields = document.getElementById('distributor-fields');
        const contactNumber = document.getElementById('contact_number');

        function toggleFields() {
            const selectedRole = roleSelect.options[roleSelect.selectedIndex].text.toLowerCase();

            if (selectedRole === 'customer') {
                customerFields.classList.remove('hidden');
                distributorFields.classList.add('hidden');
                contactNumber.required = true;
                customerFields.querySelectorAll('input').forEach(el => el.required = true);
                distributorFields.querySelectorAll('input, select').forEach(el => el.required = false);
            } else if (selectedRole === 'distributor') {
                distributorFields.classList.remove('hidden');
                customerFields.classList.add('hidden');
                contactNumber.required = true;
                distributorFields.querySelectorAll('input, select').forEach(el => el.required = true);
                customerFields.querySelectorAll('input').forEach(el => el.required = false);
            } else {
                customerFields.classList.add('hidden');
                distributorFields.classList.add('hidden');
                contactNumber.required = false;
            }
        }

        roleSelect.addEventListener('change', toggleFields);
        toggleFields(); // trigger on load
    </script>
</x-app-layout>
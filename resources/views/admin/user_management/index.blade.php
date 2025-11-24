<x-app-layout>
    <div class="container p-4 mx-auto">
        <h1 class="mb-4 text-2xl font-bold">All Users</h1>
        <a href="{{ route('user_management.create') }}" class="px-4 py-2 text-white bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded">
            <i class='bx bx-add-to-queue'></i> Add New User
        </a>

        <!-- Role Selection -->
        <div class="mt-4">
            <button id="showCustomers" class="px-4 py-2 mr-2 text-white bg-blue-500 rounded">Show Customers</button>
            <button id="showDistributors" class="px-4 py-2 mr-2 text-white bg-green-500 rounded">Show Distributors</button>
            <button id="showOtherRoles" class="px-4 py-2 text-white bg-gray-500 rounded">Show Admin</button>
        </div>
    </div>

    <section class="container px-4 mx-auto">
        <div class="flex flex-col">
            <!-- Table for Customers -->
            <div id="customerTableContainer" style="display: none;">
                <h2 class="mt-6 text-xl font-semibold">Customers</h2>
                <div class="flex justify-end mb-2">
                    <a href="{{ route('user_management.export', ['role' => 'customer']) }}" class="px-4 py-2 mr-2 text-white bg-blue-600 rounded">
                        Export Customers
                    </a>
                </div>
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div class="overflow-hidden border border-gray-200 md:rounded-lg">
                            <table id="customerTable" class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left text-gray-500">User Id</th>
                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left text-gray-500">Name</th>
                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left text-gray-500">Email</th>
                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left text-gray-500">Status</th>
                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left text-gray-500">Actions</th>
                                        <!-- <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left text-gray-500">Referral Code</th> -->
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table for Distributors -->
            <div id="distributorTableContainer" style="display: none;">
                <h2 class="mt-6 text-xl font-semibold">Distributors</h2>
                <div class="flex justify-end mb-2">
                    <a href="{{ route('user_management.export', ['role' => 'distributor']) }}" class="px-4 py-2 text-white bg-green-600 rounded">
                        Export Distributors
                    </a>
                </div>
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div class="overflow-hidden border border-gray-200 md:rounded-lg">
                            <table id="distributorTable" class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left text-gray-500">User Id</th>
                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left text-gray-500">Name</th>
                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left text-gray-500">Email</th>
                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left text-gray-500">Distributor Type</th>
                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left text-gray-500">Status</th>
                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left text-gray-500">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table for Other Roles -->
            <div id="otherRolesTableContainer" style="display: none;">
                <h2 class="mt-6 text-xl font-semibold">Admin</h2>
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div class="overflow-hidden border border-gray-200 md:rounded-lg">
                            <table id="otherRolesTable" class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left text-gray-500">User Id</th>
                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left text-gray-500">Name</th>
                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left text-gray-500">Email</th>
                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left text-gray-500">Role</th>
                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left text-gray-500">Status</th>
                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left text-gray-500">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTables
            let customerTable = $('#customerTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('user_management.index') }}",
                    data: function(d) {
                        d.role = 'customer'; // Filter by customer role
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: null, // Use null since we will provide a custom data source
                        name: 'name', // The name you want for the combined column
                        render: function(data, type, row) {
                            return row.first_name + ' ' + row.last_name;
                        }
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            let distributorTable = $('#distributorTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('user_management.index') }}",
                    data: function(d) {
                        d.role = 'distributor'; // Filter by distributor role
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'distributor_type',
                        name: 'distributor_type',
                        render: function(data, type, row) {
                            const types = {
                                1: 'Regional Distributor',
                                2: 'Provincial Distributor',
                                3: 'City Distributor',
                                4: 'Reseller Distributor'
                            };
                            return types[data] ?? data; // Show text if mapping exists, else the number
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });


            let otherRolesTable = $('#otherRolesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('user_management.index') }}",
                    data: function(d) {
                        d.role = 'other'; // Filter for other roles
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Role Selection Functionality
            $('#showCustomers').on('click', function() {
                $('#customerTableContainer').show();
                $('#distributorTableContainer').hide();
                $('#otherRolesTableContainer').hide();
            });

            $('#showDistributors').on('click', function() {
                $('#customerTableContainer').hide();
                $('#distributorTableContainer').show();
                $('#otherRolesTableContainer').hide();
            });

            $('#showOtherRoles').on('click', function() {
                $('#customerTableContainer').hide();
                $('#distributorTableContainer').hide();
                $('#otherRolesTableContainer').show();
            });
        });

        // Handle status checkbox toggle
        $(document).on('change', '.status-checkbox', function() {
            var checkbox = $(this); // Save reference to the checkbox
            var userId = checkbox.data('id');
            var status = checkbox.is(':checked') ? 'active' : 'inactive';

            $.ajax({
                url: '{{ route("user_management.update_status") }}',
                type: 'POST',
                data: {
                    id: userId,
                    status: status,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Update the text next to checkbox without redrawing the cell
                        checkbox.next('.status-text').text(status.charAt(0).toUpperCase() + status.slice(1));
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Failed to update status.');
                    // Revert checkbox if error occurs
                    checkbox.prop('checked', !checkbox.is(':checked'));
                }
            });
        });
    </script>
</x-app-layout>
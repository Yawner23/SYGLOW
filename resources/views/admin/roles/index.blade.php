<x-app-layout>
    <div class="container p-4 mx-auto">
        <h1 class="mb-4 text-2xl font-bold">All Roles</h1>
        <a href="{{ route('roles.create') }}" class="px-4 py-2 text-white bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded">
            <i class='bx bx-add-to-queue'></i> Add New Role
        </a>
    </div>

    <section class="container px-4 mx-auto">
        <div class="flex flex-col">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden border border-gray-200 md:rounded-lg">
                        <table id="roleTable" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500">Role ID</th>
                                    <th class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500">Role Name</th>
                                    <th class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500">Permissions</th>
                                    <th class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- DataTables will fill this -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#roleTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('roles.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'permissions',
                        name: 'permissions'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
</x-app-layout>
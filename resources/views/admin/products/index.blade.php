<x-app-layout>
    <div class="container p-4 mx-auto">
        <h1 class="mb-4 text-2xl font-bold">Products</h1>
        <a href="{{ route('products.create') }}" class="px-4 py-2 text-white bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded"><i class='bx bx-add-to-queue'></i> Add New Products</a>
    </div>

    <section class="container px-4 mx-auto">
        <div class="flex flex-col">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden border border-gray-200 md:rounded-lg">
                        <table id="product_managementTable" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500">
                                        <div class="flex items-center gap-x-3">
                                            <span>ID</span>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500">
                                        Product Type
                                    </th>
                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500">
                                        Product Name
                                    </th>

                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500">
                                        Quantity
                                    </th>
                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500">
                                        Status
                                    </th>
                                    <th scope="col" class="relative py-3.5 px-4">
                                        <span class="sr-only">Actions</span>
                                    </th>
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
            var table = $('#product_managementTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('products.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    }, {
                        data: 'product_type',
                        name: 'product_type'
                    },
                    {
                        data: 'product_name',
                        name: 'product_name'
                    },

                    {
                        data: 'quantity',
                        name: 'quantity'
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

            $(document).on('change', '.status-checkbox', function() {
                var checkbox = $(this);
                var id = checkbox.data('id');
                var status = checkbox.is(':checked') ? 'new_arrival' : 'old_products';

                $.ajax({
                    url: "{{ route('products.updateStatus') }}",
                    type: 'POST',
                    data: {
                        id: id,
                        status: status,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            table.ajax.reload(); // Reload the table data
                            console.log('Status updated successfully');
                        } else {
                            console.log('Failed to update status');
                        }
                    },
                    error: function() {
                        console.log('Error occurred while updating status');
                    }
                });
            });
        });
    </script>

</x-app-layout>
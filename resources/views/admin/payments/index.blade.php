<x-app-layout>
    <div class="container p-4 mx-auto flex items-center justify-between">
        <h1 class="text-2xl font-bold">Payments</h1>

        <a href="{{ route('payments.printAll') }}"
            target="_blank"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 text-sm font-semibold text-white bg-gray-800 rounded-lg hover:bg-gray-900 transition">
            <i class="bx bx-printer text-xl"></i>
            Print All Receipts
        </a>
    </div>

    <section class="container px-4 mx-auto">

        {{-- FILTER TOOLBAR --}}
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Order Filters</h2>

            <button id="toggleFilters"
                type="button"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                <i class="bx bx-filter-alt text-xl"></i>
                Filters
            </button>
        </div>

        {{-- FILTER PANEL --}}
        <div id="filtersPanel" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-3 mb-6 hidden">
            {{-- 1. Time created (date) --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Time created (date)</label>
                <input type="date" id="filter_time_created"
                    class="w-full px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring-1 focus:ring-gray-400">
            </div>

            {{-- 2. Customer username --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Customer username</label>
                <input type="text" id="filter_customer"
                    placeholder="Username / name"
                    class="w-full px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring-1 focus:ring-gray-400">
            </div>

            {{-- 3. Product --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Product</label>
                <input type="text" id="filter_product"
                    placeholder="Product name"
                    class="w-full px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring-1 focus:ring-gray-400">
            </div>

            {{-- 4. Label status --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Label status</label>
                <select id="filter_label_status"
                    class="w-full px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring-1 focus:ring-gray-400">
                    <option value="">All</option>
                    <option value="unprinted">Unprinted</option>
                    <option value="printed">Printed</option>
                    <option value="printing_failed">Printing failed</option>
                </select>
            </div>

            {{-- 5. Payment method --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Payment method</label>
                <select id="filter_payment_method"
                    class="w-full px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring-1 focus:ring-gray-400">
                    <option value="">All</option>
                    <option value="cod">COD</option>
                    <option value="Hitpay">HitPay</option>
                </select>
            </div>


            {{-- 6. Order status --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Order status</label>
                <select id="filter_order_status"
                    class="w-full px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring-1 focus:ring-gray-400">
                    <option value="">All</option>
                    <option value="cod_pending">Pending</option>
                    <option value="Paid">Paid</option>
                    <!-- <option value="canceled">Canceled</option> -->
                    <option value="failed_delivery">Failed delivery</option>
                    <option value="return_to_sender">Return to sender</option>
                    <option value="completed">Completed / Delivered</option>
                </select>
            </div>

            {{-- Apply / Clear --}}
            <div class="flex items-end gap-2">
                <button id="applyFilters"
                    type="button"
                    class="px-4 py-2 text-sm font-semibold text-white bg-gray-800 rounded-lg hover:bg-gray-900">
                    Apply
                </button>
                <button id="clearFilters"
                    type="button"
                    class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    Clear
                </button>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="flex flex-col">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden border border-gray-200 md:rounded-lg">
                        <table id="paymentTable" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500">ID</th>
                                    <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500">WayBill Number</th>
                                    <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500">Customer</th>
                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500">Delivery Address</th>
                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500">Total</th>
                                    <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500">
                                        Role
                                    </th>
                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500">
                                        Label Status
                                    </th>
                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500">Status</th>
                                    <th scope="col" class="relative py-3.5 px-4">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- <a href="{{ url('payments/${row.id}/shipping') }}"><i class='text-xl text-black bx bxs-plus-circle'>Shipping Fee</i></a> -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#paymentTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('payments.index') }}",
                    data: function(d) {
                        d.time_created = $('#filter_time_created').val();
                        d.customer_name = $('#filter_customer').val(); // 👈 CHANGE HERE
                        d.product = $('#filter_product').val();
                        d.label_status = $('#filter_label_status').val();
                        d.payment_method = $('#filter_payment_method').val();
                        d.order_status = $('#filter_order_status').val();
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'waybill_number',
                        name: 'waybill_number'
                    },
                    {
                        data: 'customer.name',
                        name: 'customer.name'
                    },
                    {
                        data: 'short_address',
                        name: 'short_address'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'label_status_text',
                        name: 'label_status_text'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: null,
                        name: 'actions',
                        render: function(data, type, row) {
                            return `
                                <div class="flex items-center space-x-2"> 
                                    <a href="{{ url('payments/${row.id}') }}"><i class='text-xl text-black bx bx-expand-alt'>View</i></a>
                                    <a href="#" class="delete-payment" data-id="${row.id}"><i class='text-4xl text-red-600 bx bx-trash'></i></a>
                                </div>
                            `;
                        },
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Toggle filter panel
            $('#toggleFilters').on('click', function() {
                $('#filtersPanel').toggleClass('hidden');
            });

            // Apply filters
            $('#applyFilters').on('click', function() {
                table.ajax.reload();
            });

            // Clear filters
            $('#clearFilters').on('click', function() {
                $('#filter_time_created').val('');
                $('#filter_customer').val('');
                $('#filter_product').val('');
                $('#filter_label_status').val('');
                $('#filter_payment_method').val('');
                $('#filter_order_status').val('');
                table.ajax.reload();
            });

            // Status checkbox change
            $(document).on('change', '.status-checkbox', function() {
                var checkbox = $(this);
                var id = checkbox.data('id');
                var status = checkbox.is(':checked') ? 'Paid' : 'unpaid';

                $.ajax({
                    url: "{{ route('payments.updateStatus') }}",
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

            // Delete payment
            $(document).on('click', '.delete-payment', function(e) {
                e.preventDefault();
                var id = $(this).data('id');

                if (confirm('Are you sure you want to delete this payment?')) {
                    $.ajax({
                        url: "{{ url('payments') }}/" + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                table.ajax.reload(); // Reload the table data
                                console.log('Payment deleted successfully');
                            } else {
                                console.log('Failed to delete payment');
                            }
                        },
                        error: function() {
                            console.log('Error occurred while deleting payment');
                        }
                    });
                }
            });
        });
    </script>

</x-app-layout>
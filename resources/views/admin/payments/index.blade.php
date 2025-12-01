<x-app-layout>
    <div class="container p-4 mx-auto">
        <h1 class="mb-4 text-2xl font-bold">Payments</h1>

    </div>

    <section class="container px-4 mx-auto">
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
                ajax: "{{ route('payments.index') }}",
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
                        data: 'delivery_address.full_address',
                        name: 'delivery_address.full_address'
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
                                <a href="#" class=" delete-payment" data-id="${row.id}"><i class='text-4xl text-red-600 bx bx-trash'></i></a>
                            </div>
                           
                        `;
                        },
                        orderable: false,
                        searchable: false
                    }
                ]
            });

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
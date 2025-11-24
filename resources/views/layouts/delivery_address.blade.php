<!-- Modal for Address Selection -->
<div id="address-modal" class="fixed inset-0 z-50 hidden bg-gray-800 bg-opacity-50" onclick="closeModal()">
    <div class="flex items-center justify-center h-full">
        <div class="w-11/12 p-6 bg-white rounded-lg md:w-2/3 lg:w-2/5">
            <div class="flex items-center justify-between">
                <h2 class="mb-4 text-2xl font-bold">Select Delivery Address</h2>
                <a href="{{url('customer_edit_profile')}}">
                    <button class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] text-2xl text-white px-4 py-2 rounded-2xl">
                        <i class='bx bx-plus-circle'></i>
                    </button>
                </a>
            </div>

            <!-- Make the address list scrollable -->
            <div id="address-list" class="space-y-4 overflow-y-auto max-h-96">
                @forelse($delivery_address as $address)
                <div class="relative text-lg">
                    <h1 class="text-2xl font-bold">{{ $address->deliver_name }}</h1>
                    <div class="absolute z-0 right-8">
                        <form action="{{ route('delivery-address.destroy', $address->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] text-2xl text-white px-4 py-2 rounded-2xl">
                                <i class='bx bx-trash'></i>
                            </button>
                        </form>
                    </div>
                    <button class="w-full select-address" data-address="{{ json_encode($address) }}">
                        <ul class="grid grid-cols-2 p-8 mt-8 text-left border rounded-xl">
                            <li>Email:</li>
                            <li>{{ $address->email_address }}</li>
                            <li>Contact Number:</li>
                            <li>{{ $address->contact_no }}</li>
                            <li>Address:</li>
                            <li>{{ $address->full_address }}</li>
                            <li>Delivery Instruction:</li>
                            <li>{{ $address->delivery_instructions }}</li>
                        </ul>
                    </button>
                </div>
                @empty
                <p>No delivery addresses available.</p>
                @endforelse
            </div>

            <button id="close-modal" class="px-4 py-2 mt-4 text-white bg-gray-500 rounded" onclick="closeModal()">Close</button>
        </div>
    </div>
</div>


<!-- JavaScript to Handle Modal Display -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('address-modal');
        const openButton = document.getElementById('change-address-button'); // Ensure this button exists

        // Function to open the modal
        function openModal() {
            modal.classList.remove('hidden');
        }

        // Function to close the modal
        window.closeModal = function() {
            modal.classList.add('hidden');
        }

        // Open modal when button is clicked
        if (openButton) {
            openButton.addEventListener('click', openModal);
        }

        // Handle address selection
        document.querySelectorAll('.select-address').forEach(button => {
            button.addEventListener('click', function() {
                const address = JSON.parse(this.getAttribute('data-address'));

                // Populate form fields with the selected address data
                document.getElementById('full_address').value = address.full_address;
                document.getElementById('barangay').value = address.barangay;
                document.getElementById('province').value = address.province;
                document.getElementById('city').value = address.city;
                document.getElementById('zip_code').value = address.zip_code;
                document.getElementById('email_address').value = address.email_address;
                document.getElementById('delivery_instructions').value = address.delivery_instructions;
                document.getElementById('contact_no').value = address.contact_no;
                document.getElementById('tel_no').value = address.tel_no;
                document.getElementById('delivery_address_id').value = address.id;

                // Close the modal after selecting an address
                closeModal();
            });
        });
    });
</script>
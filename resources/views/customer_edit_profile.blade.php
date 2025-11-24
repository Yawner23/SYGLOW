@extends('layouts.sidebar')

@section('title', 'Home')

@section('content')
<div class="flex flex-col justify-between p-8 text-4xl md:flex-row">
    <h1> Profile</h1>
    @if(Auth::user()->hasPermission('distributor'))
    <h1>Welcome, {{ Auth::user()->distributor->name }}!</h1>
    @elseif(Auth::user()->hasPermission('customer'))
    <h1>Welcome, {{ Auth::user()->customer->first_name }}!</h1>
    @endif
</div>



<div class="grid grid-cols-1 gap-20 p-8 lg:grid-cols-2">

    <div class="grid grid-cols-2 ">

        <!-- Delivery Address Form -->
        <!-- Delivery Address Form -->
        <form id="deliveryForm" method="POST" action="{{ route('delivery-address.store') }}" class="flex flex-col col-span-2 space-y-4">
            @csrf
            <input hidden type="text" name="customer_id" id="customer_id" readonly value="{{ Auth::user()->id }}">
            <input hidden type="text" name="id" id="delivery_address_id" readonly>
            <div class="flex flex-col col-span-2">
                <label class="my-4 text-2xl font-bold" for="full_address">Delivery</label>
            </div>

            <div class="flex flex-col col-span-2">
                <input placeholder="Full Address" class="w-full p-4 h-full border-[#f56e98] rounded-lg cursor-pointer" type="text"
                    name="full_address"
                    id="full_address"
                    readonly
                    onclick="openMapModal()">
            </div>

            <section class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="flex flex-col">
                    <select name="province" id="province" class="w-full p-4 h-full border-[#f56e98] text-gray-400 rounded-lg">
                        <option value="">Select a Province</option>
                    </select>
                </div>

                <div class="flex flex-col">
                    <select name="city" id="city" class="w-full p-4 h-full border-[#f56e98] text-gray-400 rounded-lg">
                        <option value="">City/Municipality</option>
                    </select>
                </div>

                <div class="flex flex-col">
                    <select name="barangay" id="barangay" class="w-full p-4 h-full border-[#f56e98] text-gray-400 rounded-lg">
                        <option value="">Barangay</option>
                    </select>
                </div>

                <div class="flex flex-col">
                    <input placeholder="Zip Code" class="w-full p-4 h-full border-[#f56e98] rounded-lg" type="text" name="zip_code" id="zip_code">
                </div>
            </section>

            <div class="flex flex-col col-span-2">
                <input placeholder="Email Address" class="w-full p-4 h-full border-[#f56e98] rounded-lg" type="text" name="email_address" id="email_address">
            </div>

            <div class="flex flex-col col-span-2">
                <input placeholder="Delivery Instruction (Optional)" class="w-full p-4 h-full border-[#f56e98] rounded-lg" type="text" name="delivery_instructions" id="delivery_instructions">
            </div>
            <div class="flex flex-col col-span-2">
                <label class="my-4 text-2xl font-bold" for="contact_no">Contact</label>
                <input placeholder="Contact No." class="w-full p-4 h-full border-[#f56e98] rounded-lg" type="tel" name="contact_no" id="contact_no">
            </div>
            <div class="flex flex-col col-span-2">
                <input placeholder="Tel No. (Optional)" class="w-full p-4 h-full border-[#f56e98] rounded-lg" type="tel" name="tel_no" id="tel_no">
            </div>
            <div class="flex flex-col col-span-2">
                <label class="my-4 text-2xl font-bold" for="deliver_name">Name As:</label>
                <input placeholder="Home Delivery Address" class="w-full p-4 h-full border-[#f56e98] rounded-lg" type="text" name="deliver_name" id="deliver_name">
            </div>
            <div class="flex items-end justify-end col-span-2">
                <button type="submit" class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg w-20 py-2 text-white border-2 border-white">
                    <i class='text-4xl bx bx-download'></i>
                </button>
            </div>
        </form>



        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const deliveryForm = document.getElementById('deliveryForm');
                const otpForm = document.getElementById('otpForm');
                const otpModal = document.getElementById('otpModal');

                // Handle delivery form submission
                deliveryForm.addEventListener('submit', function(event) {
                    event.preventDefault();

                    const formData = new FormData(deliveryForm);

                    fetch('{{ route("send.otp") }}', {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': formData.get('_token') // Ensure CSRF token is correctly retrieved
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Open the OTP verification modal
                                otpModal.classList.remove('hidden');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });

                // Handle OTP form submission
                otpForm.addEventListener('submit', function(event) {
                    event.preventDefault();

                    const otp = document.getElementById('otp').value;
                    const formData = new FormData();
                    formData.append('otp', otp);
                    formData.append('_token', document.querySelector('input[name="_token"]').value);

                    fetch('{{ route("delivery_verify") }}', {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': formData.get('_token') // Ensure CSRF token is correctly retrieved
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // OTP verified successfully
                                deliveryForm.submit(); // Submit the original form
                            } else {
                                // OTP verification failed
                                alert('Invalid OTP. Please try again.');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            });
        </script>


    </div>


    <!-- Delivery Address List -->
    <div class="space-y-4">
        @forelse($delivery_address as $address)
        <div class="relative text-lg">
            <h1 class="text-2xl font-bold">{{ $address->deliver_name }}</h1>
            <div class="absolute z-0 right-8">
                <form action="{{ route('delivery-address.destroy', $address->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] text-4xl text-white px-4 py-2 rounded-2xl">
                        <i class='bx bx-trash'></i>
                    </button>
                </form>
            </div>
            <button class="w-full select-address" data-address="{{ $address }}">
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
    <!-- End Delivery Address -->
</div>

<div id="mapModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900 bg-opacity-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative w-full max-w-4xl p-6 bg-white rounded-lg shadow-lg">
            <button id="closeModal" class="absolute top-0 right-0 mt-4 mr-4 text-gray-700 hover:text-gray-900">
                <i class="text-4xl bx bx-x"></i>
            </button>

            <h2 class="mb-4 text-2xl font-bold">Select Location</h2>

            <!-- Input field for manual address search -->
            <div class="relative flex items-center space-x-4">
                <input id="address-input" type="text" placeholder="Enter address" class="w-full p-4 border border-gray-300 rounded-lg" />
                <button id="searchAddress" class="px-4 py-2 text-white bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg">Search</button>

                <!-- Dropdown for address suggestions -->
                <ul id="suggestions" class="absolute left-0 z-50 hidden w-full mt-1 overflow-y-auto bg-white border border-gray-300 rounded-lg top-full max-h-56">
                </ul>
            </div>

            <!-- Leaflet Map Container -->
            <div id="map" class="relative z-0 w-full mt-4 h-96"></div>

            <div class="mt-4">
                <button id="confirmLocation" class="px-6 py-2 text-white bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg">Confirm Location</button>
            </div>
        </div>
    </div>
</div>
<!-- OTP Verification Modal -->
<div id="otpModal" class="fixed inset-0 flex items-center justify-center hidden bg-gray-900 bg-opacity-50">
    <div class="p-6 bg-white rounded-lg shadow-lg">
        <h2 class="mb-4 text-2xl font-bold">Verify OTP</h2>
        <form id="otpForm">
            @csrf
            <input type="text" name="otp" id="otp" placeholder="Enter OTP" class="w-full p-4 mb-4 border rounded-lg">
            <button type="submit" class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg w-full py-2 text-white border-2 border-white">
                Verify
            </button>
        </form>
    </div>
</div>
<script>
    let map, marker;

    document.addEventListener('DOMContentLoaded', function() {
        // Open the map modal when clicking the "Full Address" input
        document.getElementById('full_address').addEventListener('click', openMapModal);

        // Close the map modal
        document.getElementById('closeModal').addEventListener('click', closeMapModal);

        // Confirm location selection
        document.getElementById('confirmLocation').addEventListener('click', function() {
            if (marker) {
                const latLng = marker.getLatLng();
                reverseGeocode(latLng.lat, latLng.lng);

                // Set the value of the full_address input field
                const address = document.getElementById('address-input').value;
                document.getElementById('full_address').value = address;

                // Close the map modal
                closeMapModal();
            }
        });


        // Search for the address when the "Search" button is clicked
        document.getElementById('searchAddress').addEventListener('click', function() {
            const address = document.getElementById('address-input').value;
            if (address) {
                searchAddress(address);
            }
        });

        // Trigger address suggestions as the user types
        document.getElementById('address-input').addEventListener('input', function() {
            const query = this.value;
            if (query.length > 2) { // Only search after 3 characters
                getSuggestions(query);
            } else {
                clearSuggestions();
            }
        });

        // Select a suggestion
        document.getElementById('suggestions').addEventListener('click', function(event) {
            if (event.target.tagName === 'LI') {
                const lat = event.target.getAttribute('data-lat');
                const lon = event.target.getAttribute('data-lon');
                const displayName = event.target.innerText;

                // Update input and hide suggestions
                document.getElementById('address-input').value = displayName;
                clearSuggestions();

                // Set the map location and marker
                const latLng = [lat, lon];
                map.setView(latLng, 13);
                if (marker) {
                    marker.setLatLng(latLng);
                } else {
                    marker = L.marker(latLng).addTo(map);
                }
            }
        });
    });

    function openMapModal() {
        document.getElementById('mapModal').classList.remove('hidden');

        if (!map) {
            // Initialize the Leaflet map
            map = L.map('map').setView([14.617538450793047, 121.04485657674584], 13); // Default location

            // Load OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Add marker on map click
            map.on('click', function(e) {
                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng).addTo(map);
                }
                reverseGeocode(e.latlng.lat, e.latlng.lng); // Update address on click
            });

            // Reverse geocode to display the initial location as address
            reverseGeocode(14.617538450793047, 121.04485657674584);
        }
    }

    function reverseGeocode(lat, lng) {
        // Use Nominatim API for reverse geocoding the coordinates
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.display_name) {
                    document.getElementById('address-input').value = data.display_name;
                } else {
                    alert('Address not found.');
                }
            })
            .catch(error => {
                console.error('Error reverse geocoding the coordinates:', error);
                alert('An error occurred while retrieving the address. Please try again.');
            });
    }


    function closeMapModal() {
        document.getElementById('mapModal').classList.add('hidden');
    }

    function searchAddress(address) {
        // Use Nominatim API for geocoding the address
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    const latLng = [data[0].lat, data[0].lon];
                    map.setView(latLng, 13); // Move map to the searched location

                    // Place or move the marker
                    if (marker) {
                        marker.setLatLng(latLng);
                    } else {
                        marker = L.marker(latLng).addTo(map);
                    }
                } else {
                    alert('Address not found. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error fetching the address:', error);
                alert('An error occurred while searching for the address. Please try again.');
            });
    }


    function getSuggestions(query) {
        // Use Nominatim API to fetch suggestions for the address
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&addressdetails=1&limit=5`)
            .then(response => response.json())
            .then(data => {
                const suggestionsList = document.getElementById('suggestions');
                clearSuggestions();

                if (data && data.length > 0) {
                    data.forEach(location => {
                        const li = document.createElement('li');
                        li.className = 'p-2 cursor-pointer hover:bg-gray-200';
                        li.textContent = location.display_name;
                        li.setAttribute('data-lat', location.lat);
                        li.setAttribute('data-lon', location.lon);
                        suggestionsList.appendChild(li);
                    });
                    suggestionsList.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error fetching suggestions:', error);
            });
    }

    function clearSuggestions() {
        const suggestionsList = document.getElementById('suggestions');
        suggestionsList.innerHTML = '';
        suggestionsList.classList.add('hidden');
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.select-address').forEach(button => {
            button.addEventListener('click', function() {
                const address = JSON.parse(this.getAttribute('data-address'));

                // Populate form fields with the selected address data
                document.getElementById('full_address').value = address.full_address;
                document.getElementById('province').value = address.province;
                document.getElementById('city').value = address.city;
                document.getElementById('barangay').value = address.barangay;
                document.getElementById('zip_code').value = address.zip_code;
                document.getElementById('email_address').value = address.email_address;
                document.getElementById('delivery_instructions').value = address.delivery_instructions;
                document.getElementById('contact_no').value = address.contact_no;
                document.getElementById('tel_no').value = address.tel_no;
                document.getElementById('deliver_name').value = address.deliver_name;
                document.getElementById('delivery_address_id').value = address.id; // Set the ID
            });
        });
    });
</script>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        const provinceSelect = document.getElementById('province');
        const citySelect = document.getElementById('city');
        const barangaySelect = document.getElementById('barangay');

        // Fetch and populate provinces
        fetch('https://psgc.gitlab.io/api/provinces/')
            .then(response => response.json())
            .then(data => {
                provinceSelect.innerHTML = '<option value="">Select a Province</option>';
                data.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.name; // Use province name for display value
                    option.setAttribute('data-code', province.code); // Store province code in data attribute
                    option.textContent = province.name;
                    provinceSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching provinces:', error));

        // Fetch and populate cities based on selected province
        provinceSelect.addEventListener('change', function() {
            const selectedProvinceCode = provinceSelect.options[provinceSelect.selectedIndex].getAttribute('data-code');
            citySelect.innerHTML = '<option value="">Select a City/Municipality</option>';
            barangaySelect.innerHTML = '<option value="">Select a Barangay</option>'; // Clear barangay options

            if (selectedProvinceCode) {
                fetch(`https://psgc.gitlab.io/api/provinces/${selectedProvinceCode}/cities-municipalities/`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.name; // Use city name for display value
                            option.setAttribute('data-code', city.code); // Store city code in data attribute
                            option.textContent = city.name;
                            citySelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching cities:', error));
            }
        });

        // Fetch and populate barangays based on selected city
        citySelect.addEventListener('change', function() {
            const selectedCityCode = citySelect.options[citySelect.selectedIndex].getAttribute('data-code');
            barangaySelect.innerHTML = '<option value="">Select a Barangay</option>';

            if (selectedCityCode) {
                fetch(`https://psgc.gitlab.io/api/cities-municipalities/${selectedCityCode}/barangays/`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(barangay => {
                            const option = document.createElement('option');
                            option.value = barangay.name; // Use barangay name for display value
                            option.setAttribute('data-code', barangay.code); // Store barangay code in data attribute (if available)
                            option.textContent = barangay.name;
                            barangaySelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching barangays:', error));
            }
        });
    });
</script>
@endsection
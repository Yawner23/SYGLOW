@extends('layouts.main-app')

@section('title', 'Member')

@section('content')

@include('layouts.navbar')
<div class="relative h-[20rem]">
    <img class="absolute object-cover w-full h-full " src="{{asset('images/All-Banner.png')}}" alt="">
    <div class="container relative max-w-screen-xl py-32 mx-auto">
        <div class="flex flex-col items-center justify-center space-y-4">
            <h1 class="text-5xl font-semibold">Be Our Member</h1>
            <h1 class="text-xl">Home >> <span class="text-[#f56e98]">Be Our Member</span> >> <span class="text-[#f56e98]">Distributors</span></h1>
        </div>
    </div>
</div>
<div class="relative w-full h-full">
    <img class="absolute object-cover w-full h-full" src="{{asset('images/Golden-Sparkling-Wave.png')}}" alt="">

    <div class="container relative max-w-screen-xl mx-auto" data-aos="zoom-in" data-aos-duration="1500">
        <div class="flex flex-col items-center justify-center">
            <img class="" src="{{ asset('images/Membership.png') }}" alt="">
            <h1 class="text-5xl font-bold -mt-[10rem]">Distributors</h1>
        </div>
        <!-- @if ($errors->any())
        <div class="p-4 mb-4 text-white bg-red-500 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif -->
        <form method="POST" enctype="multipart/form-data" action="{{ route('register') }}" class="grid grid-cols-1 gap-8 px-4 mt-40 mb-8 md:grid-cols-2">
            @csrf

            <input type="hidden" name="role" value="distributor">

            <!-- Distributor Type -->
            <div class="grid grid-cols-1 col-span-2 space-y-2 md:grid-cols-2">
                <div class="flex flex-row items-center space-x-4">
                    <input id="regional_distributor" type="radio" name="distributor_type" value="1" class="w-4 h-4 text-[#f56e98] bg-white border-black focus:ring-[#f56e98] focus:ring-2" {{ old('distributor_type') == 1 ? 'checked' : '' }} required>
                    <label class="font-bold" for="regional_distributor">Regional Distributor</label>
                </div>
                <div class="flex flex-row items-center space-x-4">
                    <input id="provincial_distributor" type="radio" name="distributor_type" value="2" class="w-4 h-4 text-[#f56e98] bg-white border-black focus:ring-[#f56e98] focus:ring-2" {{ old('distributor_type') == 2 ? 'checked' : '' }} required>
                    <label class="font-bold" for="provincial_distributor">Provincial Distributor</label>
                </div>
                <div class="flex flex-row items-center space-x-4">
                    <input id="city_distributor" type="radio" name="distributor_type" value="3" class="w-4 h-4 text-[#f56e98] bg-white border-black focus:ring-[#f56e98] focus:ring-2" {{ old('distributor_type') == 3 ? 'checked' : '' }} required>
                    <label class="font-bold" for="city_distributor">City Distributor</label>
                </div>
                <div class="flex flex-row items-center space-x-4">
                    <input id="reseller_distributor" type="radio" name="distributor_type" value="4" class="w-4 h-4 text-[#f56e98] bg-white border-black focus:ring-[#f56e98] focus:ring-2" {{ old('distributor_type') == 4 ? 'checked' : '' }} required>
                    <label class="font-bold" for="reseller_distributor">Reseller Distributor</label>
                </div>
                @error('distributor_type')
                <p class="col-span-2 mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Location Fields -->
            <div class="grid grid-cols-1 col-span-2 gap-8 md:grid-cols-2">
                <div class="flex flex-col">
                    <label class="font-bold" for="region">Region</label>
                    <select id="region" name="region" class="p-2 border border-gray-300 rounded">
                        <option value="">Select a Region</option>
                    </select>
                    @error('region')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col">
                    <label class="font-bold" for="province">Province</label>
                    <select id="province" name="province" class="p-2 border border-gray-300 rounded">
                        <option value="">Select a Province</option>
                    </select>
                    @error('province')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col">
                    <label class="font-bold" for="city">City</label>
                    <select id="city" name="city" class="p-2 border border-gray-300 rounded">
                        <option value="">Select a City/Municipality</option>
                    </select>
                    @error('city')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col">
                    <label class="font-bold" for="brgy">Brgy.</label>
                    <select id="barangay" name="brgy" class="p-2 border border-gray-300 rounded">
                        <option value="">Select a Barangay</option>
                    </select>
                    @error('brgy')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Personal Info -->
            <div class="grid grid-cols-1 col-span-2 gap-8 md:grid-cols-2">
                <div class="flex flex-col">
                    <label class="font-bold" for="name">Name</label>
                    <input type="text" id="name" name="name" class="p-2 border border-gray-300 rounded" value="{{ old('name') }}" required>
                    @error('name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-col">
                    <label class="font-bold" for="code">Referral Code</label>
                    <input type="text" id="code" name="code" class="p-2 border border-gray-300 rounded" value="{{ old('code') }}" required>
                    @error('code')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-col">
                    <label class="font-bold" for="email">Email</label>
                    <input type="text" id="email" name="email" class="p-2 border border-gray-300 rounded" value="{{ old('email') }}" required>
                    @error('email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-col">
                    <label class="font-bold" for="contact_number">Contact Number</label>
                    <input type="number" id="contact_number" name="contact_number" class="p-2 border border-gray-300 rounded" value="{{ old('contact_number') }}" required>
                    @error('contact_number')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- File Uploads -->
            <div class="grid grid-cols-1 col-span-2 gap-8 md:grid-cols-2">
                <div class="flex flex-row items-center ">
                    <label class="flex items-center justify-center space-x-4 font-bold cursor-pointer" for="valid_id_path">
                        <span>
                            <i class='p-2 text-5xl text-gray-400 bg-white border-2 bx bxs-image'></i>
                        </span>
                        <span class="text-[#f56e98] ">*</span>
                        Valid Id
                    </label>
                    <input type="file" id="valid_id_path" name="valid_id_path" class="hidden" accept="image/*">
                    <div id="valid_id_preview" class="mt-2"></div>
                    @error('valid_id_path')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-row items-center">
                    <label class="flex items-center justify-center space-x-4 font-bold cursor-pointer" for="photo_with_background_path">
                        <span>
                            <i class='p-2 text-5xl text-gray-400 bg-white border-2 bx bxs-image'></i>
                        </span>
                        <span class="text-[#f56e98] ">*</span>
                        Photo with a plain background
                    </label>
                    <input type="file" id="photo_with_background_path" name="photo_with_background_path" hidden accept="image/*">
                    <div id="photo_with_background_preview" class="mt-2"></div>
                    @error('photo_with_background_path')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-row items-center space-x-4">
                    <label class="flex items-center justify-center space-x-4 font-bold cursor-pointer" for="selfie_with_id_path">
                        <span>
                            <i class='p-2 text-5xl text-gray-400 bg-white border-2 bx bxs-image'></i>
                        </span>
                        <span class="text-[#f56e98] ">*</span>
                        Selfie with Id
                    </label>
                    <input type="file" id="selfie_with_id_path" name="selfie_with_id_path" hidden accept="image/*">
                    <div id="selfie_with_id_preview" class="mt-2"></div>
                    @error('selfie_with_id_path')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-center col-span-2 py-20">
                <button type="submit" class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg w-80 py-2 text-white border-2 border-white">Submit</button>
            </div>
        </form>
    </div>
</div>

@include('layouts.member')
@include('layouts.footer')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const file = input.files[0];

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="object-cover w-32 h-32 border-2 border-gray-300 rounded">`;
                };

                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
            }
        }

        document.getElementById('valid_id_path').addEventListener('change', function() {
            previewImage(this, 'valid_id_preview');
        });

        document.getElementById('photo_with_background_path').addEventListener('change', function() {
            previewImage(this, 'photo_with_background_preview');
        });

        document.getElementById('selfie_with_id_path').addEventListener('change', function() {
            previewImage(this, 'selfie_with_id_preview');
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const regionSelect = document.getElementById('region');
        const provinceSelect = document.getElementById('province');
        const citySelect = document.getElementById('city');
        const barangaySelect = document.getElementById('barangay');

        // Fetch and populate regions
        fetch('https://psgc.gitlab.io/api/regions/')
            .then(response => response.json())
            .then(data => {
                regionSelect.innerHTML = '<option value="">Select a Region</option>';
                data.forEach(region => {
                    const option = document.createElement('option');
                    option.value = region.name; // Save name only
                    option.setAttribute('data-code', region.code); // store code for API calls
                    option.textContent = region.name;
                    regionSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching regions:', error));

        // Fetch and populate provinces
        regionSelect.addEventListener('change', function() {
            const selectedRegionCode = regionSelect.options[regionSelect.selectedIndex].getAttribute('data-code');
            provinceSelect.innerHTML = '<option value="">Select a Province</option>';
            citySelect.innerHTML = '<option value="">Select a City/Municipality</option>';
            barangaySelect.innerHTML = '<option value="">Select a Barangay</option>';

            if (selectedRegionCode) {
                fetch(`https://psgc.gitlab.io/api/regions/${selectedRegionCode}/provinces/`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(province => {
                            const option = document.createElement('option');
                            option.value = province.name; // Save name
                            option.setAttribute('data-code', province.code);
                            option.textContent = province.name;
                            provinceSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching provinces:', error));
            }
        });

        // Fetch and populate cities
        provinceSelect.addEventListener('change', function() {
            const selectedProvinceCode = provinceSelect.options[provinceSelect.selectedIndex].getAttribute('data-code');
            citySelect.innerHTML = '<option value="">Select a City/Municipality</option>';
            barangaySelect.innerHTML = '<option value="">Select a Barangay</option>';

            if (selectedProvinceCode) {
                fetch(`https://psgc.gitlab.io/api/provinces/${selectedProvinceCode}/cities-municipalities/`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.name; // Save name
                            option.setAttribute('data-code', city.code);
                            option.textContent = city.name;
                            citySelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching cities:', error));
            }
        });

        // Fetch and populate barangays
        citySelect.addEventListener('change', function() {
            const selectedCityCode = citySelect.options[citySelect.selectedIndex].getAttribute('data-code');
            barangaySelect.innerHTML = '<option value="">Select a Barangay</option>';

            if (selectedCityCode) {
                fetch(`https://psgc.gitlab.io/api/cities-municipalities/${selectedCityCode}/barangays/`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(barangay => {
                            const option = document.createElement('option');
                            option.value = barangay.name; // Save name only
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
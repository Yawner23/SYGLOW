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
                option.value = province.code; // Use province code for fetching cities
                option.textContent = province.name;
                provinceSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching provinces:', error));

    // Fetch and populate cities based on selected province
    provinceSelect.addEventListener('change', function() {
        const selectedProvinceCode = provinceSelect.value;
        citySelect.innerHTML = '<option value="">Select a City/Municipality</option>';
        barangaySelect.innerHTML = '<option value="">Select a Barangay</option>'; // Clear barangay options

        if (selectedProvinceCode) {
            fetch(`https://psgc.gitlab.io/api/provinces/${selectedProvinceCode}/cities-municipalities/`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.code; // Use city code for fetching barangays
                        option.textContent = city.name;
                        citySelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching cities:', error));
        }
    });

    // Fetch and populate barangays based on selected city
    citySelect.addEventListener('change', function() {
        const selectedCityCode = citySelect.value;
        barangaySelect.innerHTML = '<option value="">Select a Barangay</option>';

        if (selectedCityCode) {
            fetch(`https://psgc.gitlab.io/api/cities/${selectedCityCode}/barangays/`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(barangay => {
                        const option = document.createElement('option');
                        option.value = barangay.code; // You might need to change this if barangay has no code
                        option.textContent = barangay.name;
                        barangaySelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching barangays:', error));
        }
    });
});

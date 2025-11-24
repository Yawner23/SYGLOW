document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('button[id^="add-to-cart-"]').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-product-id');

            fetch('/add-to-cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Fetch product details and update the modal
                    return fetch(`/product-details/${productId}`);
                }
            })
            .then(response => response.json())
            .then(product => {
                // Update modal content
                document.querySelector('#modal-product-image').src = `/images/uploads/product_images/${product.image}`;
                document.querySelector('#modal-product-name').textContent = product.product_name;
                document.querySelector('#modal-product-price').textContent = `₱${product.price}`;
                
                // Show the modal
                document.querySelector('#modal').classList.remove('hidden');
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Close modal functionality
    document.querySelector('#close-modal').addEventListener('click', function () {
        document.querySelector('#modal').classList.add('hidden');
    });
});

<div id="modal" class="fixed inset-0 z-50 flex items-end justify-end hidden">
    <div id="modal-overlay" class="absolute inset-0 bg-gray-500 bg-opacity-75"></div>
    <div id="modal-content" class="relative w-full h-screen max-w-md bg-white shadow-xl sm:w-96 md:w-1/2 lg:w-2/3 xl:w-1/3">
        <!-- Modal Header -->
        <div class="flex justify-between px-4 py-2 border-b">
            <h2 class="text-2xl font-semibold">Your Cart</h2>
        </div>
        <!-- Modal Body -->
        <div class="p-4 overflow-y-auto max-h-[calc(100vh-14rem)]">

        </div>
        <!-- Modal Footer -->
        <div class="absolute bottom-0 w-full">
            <div class="flex w-full text-center border-t">
                <h1 class="w-full px-3 py-4 bg-transparent">Total</h1>
                <h1 class="w-full px-3 py-4 bg-transparent">₱0.00</h1>
            </div>
            <div class="flex w-full">
                <div class="w-full">
                    <button id="checkout-button" class="w-full px-3 py-4 bg-gradient-to-t from-[#f590b0] to-[#f56e98] text-white">CHECKOUT</button>
                </div>
                <div class="w-full">
                    <button id="close-modal" class="w-full px-3 py-4 bg-[#fdebdd]">CLOSE CART</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('modal');
        const modalOverlay = document.getElementById('modal-overlay');
        const modalContent = document.getElementById('modal-content');
        const checkoutButton = document.getElementById('checkout-button');
        let cartProducts = JSON.parse(localStorage.getItem('cartProducts')) || []; // Retrieve from localStorage or initialize empty

        function updateModalContent() {
            const productContainer = modalContent.querySelector('.p-4');
            productContainer.innerHTML = '';

            cartProducts.forEach(product => {
                productContainer.innerHTML += `
           <div class="flex flex-row items-center gap-2 mb-4">
    <img class="bg-[#fdebdd] object-contain px-2 py-8 h-[10rem] w-[10rem]" src="${product.image}" alt="">
    <div class="w-full space-y-2">
        <div class="relative">
            <h1 class="text-lg font-semibold">${product.name}</h1>
            <button type="button" class="absolute top-0 right-0 text-red-600 bg-transparent h-11" data-action="remove" data-id="${product.id}">
                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <p class="text-[#f590b0] font-bold">
            ${product.discount_price && product.discount_price > 0 
                ? `<span class="line-through text-gray-400">₱${product.price}</span> ₱${product.discount_price}` 
                : `₱${product.price}`
            }
        </p>
        <div class="relative flex items-center max-w-[8rem]">
            <button type="button" class="p-3 bg-white border border-gray-300 hover:bg-[#f56e98] rounded-s-lg h-11 text-[#f56e98] hover:text-white" data-action="decrease" data-id="${product.id}">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                </svg>
            </button>
            <input type="text" class="bg-white border-x-0 border-gray-300 h-11 text-center text-[#f56e98] text-sm block w-full py-2.5" value="${product.quantity}" readonly />
            <button type="button" class="p-3 bg-white border border-gray-300 hover:bg-[#f56e98] rounded-e-lg h-11 text-[#f56e98] hover:text-white" data-action="increase" data-id="${product.id}">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                </svg>
            </button>
        </div>
    </div>
</div>

        `;
            });

            let totalAmount = cartProducts.reduce((total, item) => {
                const effectivePrice = item.discount_price && item.discount_price > 0 ? item.discount_price : item.price;
                return total + parseFloat(effectivePrice) * item.quantity;
            }, 0);
            modalContent.querySelector('.w-full.text-center.border-t h1:last-child').textContent = `₱${totalAmount.toFixed(2)}`;
        }

        updateModalContent();

        document.querySelectorAll('#open-modal').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const productName = this.getAttribute('data-product-name');
                const productPrice = parseFloat(this.getAttribute('data-product-price'));
                const productDiscountPrice = parseFloat(this.getAttribute('data-product-discount_price'));
                const productImage = this.getAttribute('data-product-image');
                const quantityInputSelector = this.getAttribute('data-quantity-input');
                let quantity = 1;

                if (quantityInputSelector) {
                    const quantityInput = document.querySelector(quantityInputSelector);
                    if (quantityInput) {
                        quantity = parseInt(quantityInput.value) || 1;
                    }
                }

                if (productId) {
                    let existingProduct = cartProducts.find(product => product.id === productId);

                    if (existingProduct) {
                        existingProduct.quantity += quantity;
                    } else {
                        const newProduct = {
                            id: productId,
                            name: productName,
                            price: productPrice,
                            discount_price: productDiscountPrice,
                            image: productImage,
                            quantity: quantity
                        };
                        cartProducts.push(newProduct);
                    }

                    localStorage.setItem('cartProducts', JSON.stringify(cartProducts));

                    updateModalContent();
                } else {
                    updateModalContent();
                }

                modal.classList.remove('hidden');
            });
        });

        modalContent.addEventListener('click', function(event) {
            const button = event.target.closest('button');
            if (button) {
                const action = button.getAttribute('data-action');
                const productId = button.getAttribute('data-id');
                let product = cartProducts.find(product => product.id === productId);

                if (product) {
                    if (action === 'increase') {
                        product.quantity += 1;
                    } else if (action === 'decrease') {
                        product.quantity = Math.max(1, product.quantity - 1);
                    } else if (action === 'remove') {
                        cartProducts = cartProducts.filter(product => product.id !== productId);
                    }

                    localStorage.setItem('cartProducts', JSON.stringify(cartProducts));

                    updateModalContent();
                }
            }
        });

        modalOverlay.addEventListener('click', function() {
            modal.classList.add('hidden');
        });

        document.getElementById('close-modal').addEventListener('click', function() {
            modal.classList.add('hidden');
        });

        if (checkoutButton) { // Check if the element exists
            checkoutButton.addEventListener('click', function() {
                const cartData = JSON.parse(localStorage.getItem('cartProducts')) || [];

                fetch(window.routeUrls.saveCart, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            cartProducts: cartData
                        })
                    })
                    .then(response => {
                        if (response.ok) {
                            // If the response is successful, redirect to the place_order route
                            window.location.href = window.routeUrls.placeOrder;
                        } else {
                            return response.json().then(data => Promise.reject(data));
                        }
                    })
                    .then(data => {
                        console.log('Success:', data);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        }
    });
</script>
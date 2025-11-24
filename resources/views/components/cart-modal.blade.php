<div id="modal" class="fixed inset-0 z-50 flex items-end justify-end hidden">
    <div id="modal-overlay" class="absolute inset-0 bg-gray-500 bg-opacity-75"></div>
    <div id="modal-content" class="relative w-full h-screen max-w-md bg-white shadow-xl sm:w-96 md:w-1/2 lg:w-2/3 xl:w-1/3">
        <!-- Modal Header -->
        <div class="flex justify-between px-4 py-2 border-b">
            <h2 class="text-2xl font-semibold">Your Cart</h2>
        </div>
        <!-- Modal Body -->
        <div class="p-4">
            <div class="flex flex-row items-center gap-2">
                <img id="modal-product-image" class="bg-[#fdebdd] object-contain px-2 py-8 h-[10rem] w-[10rem]" src="" alt="">
                <div class="space-y-2">
                    <h1 id="modal-product-name" class="text-lg font-semibold">Product Name</h1>
                    <p id="modal-product-description">100ml</p>
                    <p id="modal-product-price" class="text-[#f590b0] font-bold">₱99.00</p>
                    <div class="relative flex items-center max-w-[8rem]">
                        <!-- Quantity controls -->
                        <button type="button" id="decrement-button" data-input-counter-decrement="quantity-input" class="p-3 bg-white border border-gray-300 hover:bg-[#f56e98] rounded-s-lg h-11 text-[#f56e98] hover:text-white">
                            <svg type="button" id="decrement-button" data-input-counter-decrement="quantity-input" class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                            </svg>
                        </button>
                        <input type="text" id="quantity-input" data-input-counter aria-describedby="helper-text-explanation" class="bg-white border-x-0 border-gray-300 h-11 text-center text-[#f56e98] text-sm block w-full py-2.5 placeholder-[#f56e98]" placeholder="0" required />
                        <button type="button" id="increment-button" data-input-counter-increment="quantity-input" class="p-3 bg-white border border-gray-300 hover:bg-[#f56e98] rounded-e-lg h-11 text-[#f56e98] hover:text-white">
                            <svg type="button" id="increment-button" data-input-counter-increment="quantity-input" class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Footer -->
        <div class="absolute bottom-0 w-full">
            <div class="flex w-full text-center border-t">
                <h1 class="w-full px-3 py-4 bg-transparent">Total</h1>
                <h1 class="w-full px-3 py-4 bg-transparent">₱198</h1>
            </div>
            <div class="flex w-full">
                <div class="w-full">
                    <a href="{{ url('/place_order') }}">
                        <button class="w-full px-3 py-4 bg-gradient-to-t from-[#f590b0] to-[#f56e98] text-white">CHECKOUT</button>
                    </a>
                </div>
                <div class="w-full">
                    <button id="close-modal" class="w-full px-3 py-4 bg-[#fdebdd]">VIEW CART</button>
                </div>
            </div>
        </div>
    </div>
</div>
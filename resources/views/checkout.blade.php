@extends('layouts.main-app')

@section('title', 'Checkout')

@section('content')
<div class="bg-[#fffcf7] min-h-screen">
    {{-- Header --}}
    <div class="bg-[#fdebdd] relative z-10">
        <div class="container flex justify-center px-4 py-2 mx-auto text-sm">
            <img src="{{ asset('images/logo-header.png') }}" alt="Logo">
        </div>
    </div>

    {{-- Vertical divider --}}
    <div class="border-l-2 border-[#f590b0] absolute inset-0 left-1/2 top-0 hidden md:block"></div>

    <div class="container relative z-50 max-w-screen-xl mx-auto py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Delivery Section --}}
            <div class="p-8 bg-white rounded shadow space-y-6">
                <div class="mb-4">
                    <a href="{{ url('/distributor_purchase') }}" class="inline-block px-4 py-2 bg-gray-200 rounded font-bold hover:bg-gray-300">
                        &larr; Back to Products
                    </a>
                </div>
                <div class="flex items-center justify-between">
                    <h1 class="text-xl font-bold">Delivery</h1>
                    <button id="change-address-button" class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg w-1/4 py-2 text-white flex items-center justify-center gap-2">
                        <i class='bx bx-plus'></i> Change Address
                    </button>
                </div>

                <section class="grid grid-cols-2 gap-4">
                    <div class="col-span-2"><input type="text" id="full_address" class="w-full p-2 border rounded" placeholder="Full Address" readonly></div>
                    <div><input type="text" id="barangay" class="w-full p-2 border rounded" placeholder="Barangay" readonly></div>
                    <div><input type="text" id="city" class="w-full p-2 border rounded" placeholder="City/Municipality" readonly></div>
                    <div><input type="text" id="province" class="w-full p-2 border rounded" placeholder="Province" readonly></div>
                    <div><input type="text" id="zip_code" class="w-full p-2 border rounded" placeholder="Zip Code" readonly></div>
                    <div class="col-span-2"><input type="text" id="email_address" class="w-full p-2 border rounded" placeholder="Email Address" readonly></div>
                    <div class="col-span-2"><input type="text" id="delivery_instructions" class="w-full p-2 border rounded" placeholder="Delivery Instructions (Optional)" readonly></div>
                </section>

                <h1 class="text-xl font-bold">Contact</h1>
                <section class="grid grid-cols-2 gap-4">
                    <div class="col-span-2"><input type="text" id="contact_no" class="w-full p-2 border rounded" placeholder="Contact No." readonly></div>
                    <div class="col-span-2"><input type="text" id="tel_no" class="w-full p-2 border rounded" placeholder="Tel No. (Optional)" readonly></div>
                </section>

                @include('layouts.delivery_address')
            </div>

            {{-- Cart & Confirm Payment --}}
            <form id="checkoutForm" action="{{ route('save.payment_summary') }}" method="POST" class="p-8 bg-white rounded shadow space-y-6">
                @csrf

                @if(session('payment_verified'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
                @endif

                {{-- Cart Items --}}
                @foreach($cart as $id => $product)
                <div class="flex flex-row space-x-4 items-center">
                    <div class="relative h-32 w-32">
                        <img class="h-32 w-32 object-contain bg-[#fdebdd] p-4 rounded" src="{{ asset('images/uploads/product_images/' . $product['image']) }}" alt="{{ $product['name'] }}">
                        <button class="absolute top-1 right-1 bg-[#f590b0] text-white rounded-full w-6 h-6 flex items-center justify-center text-sm">
                            {{ $product['quantity'] }}
                        </button>
                    </div>

                    <div class="flex-1">
                        <h2 class="text-2xl font-semibold">{{ $product['name'] }}</h2>
                        <p>₱{{ number_format($product['price'], 2) }}</p>
                        <p>Subtotal: ₱{{ number_format($product['price'] * $product['quantity'], 2) }}</p>
                    </div>

                    <input type="hidden" name="product_id[]" value="{{ $id }}">
                    <input type="hidden" name="quantity[]" value="{{ $product['quantity'] }}">
                    <input type="hidden" name="subtotal[]" value="{{ $product['price'] * $product['quantity'] }}">
                </div>
                @endforeach

                {{-- Total --}}
                <div class="flex justify-between font-bold">
                    <p>Total</p>
                    <p id="displayTotal">₱{{ number_format($totalAmount, 2) }}</p>
                </div>

                {{-- Hidden Inputs --}}
                <input type="hidden" name="customer_id" value="{{ Auth::user()->id }}">
                <input type="hidden" name="delivery_address_id" id="delivery_address_id">
                <input type="hidden" name="total" value="{{ $totalAmount }}" id="hiddenTotal">
                <input type="hidden" name="shipping_fee" id="hiddenShippingFee" value="0">

                {{-- Confirm Payment Button --}}
                <button type="button" id="showOrderSummary" class="w-full py-2 bg-[#f590b0] text-white rounded-lg font-bold">Confirm Payment</button>
            </form>
        </div>
    </div>
</div>

{{-- Order Summary Modal --}}
<div id="orderSummaryModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
    <div class="w-11/12 md:w-1/3 p-6 bg-white rounded-lg shadow-xl">
        <h2 class="text-2xl font-bold text-center border-b pb-2 mb-4">Order Summary</h2>
        <div class="space-y-4">
            <div id="modalProducts"></div>
            <div class="flex justify-between">
                <p>Weight</p>
                <p id="modalWeight">0</p>
            </div>
            <div class="flex justify-between">
                <p>Shipping Fee</p>
                <p id="modalShippingFee">₱0.00</p>
            </div>
            <div class="flex justify-between font-bold">
                <p>Total</p>
                <p id="modalGrandTotal">₱0.00</p>
            </div>
            <div class="flex space-x-4 mt-4">
                <button id="modalConfirmPayment" class="w-full py-2 bg-[#f590b0] text-white font-bold rounded">Confirm Payment</button>
                <button id="modalClose" class="w-full py-2 bg-gray-200 rounded font-bold hover:bg-gray-300">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {

        const cartData = @json($cart);
        const cart = Array.isArray(cartData) ? cartData : Object.values(cartData);
        const totalAmount = parseFloat(@json($totalAmount));

        const showModalBtn = document.getElementById("showOrderSummary");
        const modal = document.getElementById("orderSummaryModal");
        const modalProducts = document.getElementById("modalProducts");
        const modalShippingFee = document.getElementById("modalShippingFee");
        const modalWeight = document.getElementById("modalWeight");
        const modalGrandTotal = document.getElementById("modalGrandTotal");
        const hiddenShippingFee = document.getElementById("hiddenShippingFee");
        const hiddenTotal = document.getElementById("hiddenTotal");
        const modalClose = document.getElementById("modalClose");
        const modalConfirm = document.getElementById("modalConfirmPayment");
        const deliveryAddressId = document.getElementById("delivery_address_id");

        // 🔥 NOW RETURNS FULL JSON INCLUDING final_weight & raw_values
        async function fetchShippingFee(addressId) {
            if (!addressId) return null;
            try {
                const response = await fetch("{{ route('calculate.shipping_fee') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    body: JSON.stringify({
                        address_id: addressId
                    }),
                });
                return await response.json(); // <-- RETURN FULL RESPONSE
            } catch (err) {
                console.error("Error fetching shipping fee:", err);
                return null;
            }
        }

        async function populateModal() {

            modalProducts.innerHTML = '';
            cart.forEach(p => {
                const div = document.createElement('div');
                div.classList.add('flex', 'justify-between', 'mb-1');
                div.innerHTML = `<span>${p.name} x${p.quantity}</span> <span>₱${(p.price * p.quantity).toFixed(2)}</span>`;
                modalProducts.appendChild(div);
            });

            // 🔥 Shipping = full response now
            const shipping = await fetchShippingFee(deliveryAddressId.value);

            if (!shipping) return;

            modalWeight.innerText = `${shipping.final_weight} KG`; // final computed weight
            modalShippingFee.innerText = `₱${parseFloat(shipping.shipping_fee).toFixed(2)}`;

            hiddenShippingFee.value = shipping.shipping_fee;

            const grandTotal = totalAmount + Number(shipping.shipping_fee);
            modalGrandTotal.innerText = `₱${grandTotal.toFixed(2)}`;
            hiddenTotal.value = grandTotal;

            console.log("Raw Weight Values:", shipping.raw_weights); // debug log
        }

        showModalBtn.addEventListener('click', () => {
            if (!deliveryAddressId.value) {
                alert("Please select a delivery address first.");
                return;
            }
            populateModal();
            modal.classList.remove('hidden');
        });

        modalClose.addEventListener('click', () => modal.classList.add('hidden'));

        modalConfirm.addEventListener('click', () => {
            document.getElementById('checkoutForm').submit();
        });

    });
</script>


@endsection
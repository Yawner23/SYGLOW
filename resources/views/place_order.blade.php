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
        <div class="mb-4">
            <a href="{{ url('/products') }}" class="inline-block px-4 py-2 bg-gray-200 rounded font-bold hover:bg-gray-300">
                &larr; Back to Products
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Delivery Section --}}
            <div class="p-8 bg-white rounded shadow space-y-6">
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

            {{-- Cart & Payment Section --}}
            <form id="payment-form" action="{{ route('your.payment.route') }}" method="POST" class="p-8 bg-white rounded shadow space-y-6">
                @csrf

                {{-- Validation Errors --}}
                @if ($errors->any())
                <div class="p-4 text-white bg-red-500 rounded">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Cart Items --}}
                @foreach($cartProducts as $product)
                <div class="flex flex-row space-x-4 items-center">
                    <div class="relative h-32 w-32">
                        <img class="h-32 w-32 object-contain bg-[#fdebdd] p-4 rounded" src="{{ $product['image'] }}" alt="{{ $product['name'] }}">
                        <button class="absolute top-1 right-1 bg-[#f590b0] text-white rounded-full w-6 h-6 flex items-center justify-center text-sm">
                            {{ $product['quantity'] }}
                        </button>
                    </div>

                    <div class="flex-1">
                        <h2 class="text-2xl font-semibold">{{ $product['name'] }}</h2>

                        @if(isset($product['discount_price']) && $product['discount_price'] > 0)
                        <p>
                            <span class="line-through text-gray-400">₱{{ number_format($product['price'], 2) }}</span>
                            <span class="text-[#f590b0] font-bold">₱{{ number_format($product['discount_price'], 2) }}</span>
                        </p>
                        @else
                        <p>₱{{ number_format($product['price'], 2) }}</p>
                        @endif

                        <p>Subtotal: ₱{{ number_format($product['subtotal'], 2) }}</p>
                    </div>


                    <input type="hidden" name="product_id[]" value="{{ $product['id'] }}">
                    <input type="hidden" name="quantity[]" value="{{ $product['quantity'] }}">
                    <input type="hidden" name="subtotal[]" value="{{ $product['subtotal'] }}">
                </div>
                @endforeach

                {{-- Total --}}
                <div class="space-y-2">
                    <div class="flex justify-between font-bold">
                        <p>Total</p>
                        <p>₱{{ number_format($totalAmount, 2) }}</p>
                    </div>
                </div>

                <input type="hidden" name="customer_id" value="{{ Auth::user()->id }}">
                <input type="hidden" name="delivery_address_id" id="delivery_address_id">
                <input type="hidden" name="total" value="{{ $totalAmount }}">

                {{-- Confirm Payment --}}
                <button type="submit" class="w-full py-2 bg-[#f590b0] text-white rounded-lg font-bold">Confirm Payment</button>
            </form>
        </div>
    </div>
</div>

{{-- Order Summary Modal --}}
<div id="orderQueryModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
    <div class="w-11/12 md:w-1/3 p-6 bg-white rounded-lg shadow-xl">
        <h2 class="text-2xl font-bold text-center border-b pb-2 mb-4">Order Summary</h2>
        <form id="orderQueryForm" action="{{ route('process.payment') }}" method="POST">
            @csrf
            <div class="space-y-4">

                {{-- Order ID --}}
                <div>
                    <label class="block text-sm font-medium">Order ID</label>
                    <input type="text" id="order_id" name="order_id" value="{{ session('payment_id') }}" readonly
                        class="w-full p-2 border rounded bg-gray-50">
                </div>

                {{-- Products --}}
                <div>
                    <label class="block text-sm font-medium">Products</label>
                    <ul id="productList" class="mt-2 space-y-1">
                        @foreach($cartProducts as $product)
                        <li class="text-sm">
                            Product Name: <span class="font-semibold">{{ $product['name'] }}</span> — Qty:
                            {{ $product['quantity'] }}
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Fees --}}
                <div class="space-y-2 border-t pt-2">
                    <div class="flex justify-between">
                        <p>Total Weight</p>
                        <p id="totalWeight">0 kg</p>
                    </div>
                    <div class="flex justify-between">
                        <p>Product Total</p>
                        <p id="productTotal">₱0.00</p>
                    </div>
                    <div class="flex justify-between">
                        <p>Shipping Fee</p>
                        <p id="combinedShippingFee">₱0.00</p>
                    </div>

                    {{-- Voucher --}}
                    <div class="space-y-2">
                        <label for="modal_voucher_code" class="font-semibold text-lg">Voucher Code (if any)</label>
                        <div class="flex gap-2">
                            <input type="text" id="modal_voucher_code" class="w-full p-2 border rounded" placeholder="Enter voucher code">
                            <button type="button" id="applyVoucherBtn" class="px-4 py-2 bg-[#f590b0] text-white rounded">Apply</button>
                        </div>
                        <p id="voucherMessage" class="text-sm mt-1"></p>

                        <div id="voucherDetails" class="hidden flex justify-between mt-1 text-green-600 font-semibold">
                            <p>Voucher Discount</p>
                            <p id="voucherDiscount">₱0.00</p>
                        </div>
                    </div>

                    {{-- Modal Payment Method --}}
                    <div class="space-y-2">
                        <label for="payment_method" class="font-semibold text-lg">Payment Method</label>
                        <select id="payment_method" name="payment_method" class="w-full p-2 border rounded">
                            <option value="online" selected>Online Payment</option>
                            <option value="cod">Cash on Delivery</option>
                        </select>
                    </div>

                    {{-- COD, VAT, Valuation Fees --}}
                    <div id="codFeeContainer" class="space-y-1 mt-1 hidden">
                        <div class="flex justify-between text-red-600 font-semibold" id="codFeeRow">COD Fee: <span>₱0.00</span></div>
                        <div class="flex justify-between text-red-600 font-semibold" id="vatFeeRow">VAT Fee: <span>₱0.00</span></div>
                        <div class="flex justify-between text-red-600 font-semibold" id="valuationFeeRow">Valuation Fee: <span>₱0.00</span></div>
                    </div>

                    <div class="flex justify-between font-bold border-t pt-2">
                        <p>Grand Total</p>
                        <p id="grandTotal">₱0.00</p>
                    </div>
                </div>

                {{-- Hidden Inputs --}}
                <input type="hidden" name="product_ids" value="{{ json_encode(session('product_id', [])) }}">
                <input type="hidden" name="subtotals" value="{{ json_encode(session('subtotal', [])) }}">
                <input type="hidden" name="discount" id="modal_discount" value="0">
                <input type="hidden" name="shipping_fee" id="hiddenShippingFee" value="0">
                <input type="hidden" name="valuation_fee" id="hiddenValuationFee" value="0">
                <input type="hidden" name="cod_fee" id="hiddenCodFee" value="0">
                <input type="hidden" name="vat_fee" id="hiddenVatFee" value="0">

                {{-- Modal Buttons --}}
                <div class="flex space-x-4 mt-4">
                    <button type="submit" id="submitOrderQuery"
                        class="w-full py-2 bg-[#f590b0] text-white font-bold rounded disabled:opacity-50">Complete
                        Order</button>
                    <button type="button" id="close-order-modal"
                        onclick="document.getElementById('orderQueryModal').classList.add('hidden')"
                        class="w-full py-2 bg-gray-200 rounded font-bold hover:bg-gray-300">Close</button>
                </div>
            </div>
        </form>
        <div id="order-result" class="mt-4 text-center"></div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const orderModal = document.getElementById("orderQueryModal");
        const submitButton = document.getElementById("submitOrderQuery");

        const grandTotalEl = document.getElementById("grandTotal");
        const voucherInput = document.getElementById("modal_voucher_code");
        const voucherMessage = document.getElementById("voucherMessage");
        const voucherDetails = document.getElementById("voucherDetails");
        const voucherDiscount = document.getElementById("voucherDiscount");
        const discountInput = document.getElementById("modal_discount");
        const paymentMethodSelect = document.getElementById("payment_method"); // modal payment method

        let voucherCodeInput = document.querySelector('input[name="voucher_code"]');
        if (!voucherCodeInput) {
            voucherCodeInput = document.createElement('input');
            voucherCodeInput.type = 'hidden';
            voucherCodeInput.name = 'voucher_code';
            voucherCodeInput.value = '';
            document.getElementById("orderQueryForm").appendChild(voucherCodeInput);
        }

        let currentDiscount = 0;
        let baseGrandTotal = 0;
        let codFee = 0,
            vatFee = 0,
            valuationFee = 0;

        function toggleCodFees() {
            const codContainer = document.getElementById('codFeeContainer');
            if (!codContainer) return;
            codContainer.style.display = paymentMethodSelect.value === "cod" ? 'block' : 'none';
        }

        function updateGrandTotal() {
            let finalTotal = baseGrandTotal - currentDiscount;
            if (paymentMethodSelect.value === "cod") {
                finalTotal += codFee + vatFee + valuationFee;
            }
            grandTotalEl.innerText = '₱' + finalTotal.toLocaleString(undefined, {
                minimumFractionDigits: 2
            });
            discountInput.value = currentDiscount;
            voucherCodeInput.value = voucherInput.value.trim();
        }

        function populateOrderData() {
            const orderId = document.getElementById("order_id").value;
            submitButton.disabled = true;

            fetch(`/order-query/${orderId}`)
                .then(res => res.json())
                .then(data => {
                    if (!data) return;
                    document.getElementById('totalWeight').innerText = (data.weight || 0) + ' kg';
                    document.getElementById('productTotal').innerText = '₱' + parseFloat(data.product_total || 0).toLocaleString();
                    const shippingFee = parseFloat(data.shipping_fee || 0);
                    document.getElementById('combinedShippingFee').innerText = '₱' + shippingFee.toLocaleString();

                    codFee = parseFloat(data.cod_fee || 0);
                    vatFee = parseFloat(data.vat_fee || 0);
                    valuationFee = parseFloat(data.valuation_fee || 0);

                    document.getElementById('hiddenShippingFee').value = shippingFee;
                    document.getElementById('hiddenCodFee').value = codFee;
                    document.getElementById('hiddenVatFee').value = vatFee;
                    document.getElementById('hiddenValuationFee').value = valuationFee;

                    // Update fee display
                    document.querySelector('#codFeeRow span').innerText = `₱${codFee.toLocaleString(undefined,{minimumFractionDigits:2})}`;
                    document.querySelector('#vatFeeRow span').innerText = `₱${vatFee.toLocaleString(undefined,{minimumFractionDigits:2})}`;
                    document.querySelector('#valuationFeeRow span').innerText = `₱${valuationFee.toLocaleString(undefined,{minimumFractionDigits:2})}`;

                    baseGrandTotal = parseFloat(data.product_total || 0) + shippingFee;
                    toggleCodFees();
                    updateGrandTotal();

                    submitButton.disabled = false;
                })
                .catch(err => console.error("Error fetching order data:", err));
        }

        paymentMethodSelect.addEventListener("change", function() {
            toggleCodFees();
            updateGrandTotal();
        });

        const applyVoucherBtn = document.getElementById("applyVoucherBtn");
        applyVoucherBtn?.addEventListener("click", () => {
            const code = voucherInput.value.trim();
            if (!code) {
                voucherMessage.textContent = "Please enter a voucher code.";
                voucherMessage.classList.add("text-red-600");
                return;
            }

            submitButton.disabled = true;
            voucherMessage.textContent = "Validating voucher...";
            voucherMessage.classList.remove("text-red-600", "text-green-600");

            fetch("{{ route('voucher.validate') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    body: JSON.stringify({
                        voucher_code: code,
                        total: baseGrandTotal
                    }),
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        currentDiscount = data.discount;
                        voucherMessage.textContent = data.message;
                        voucherMessage.classList.add("text-green-600");
                        voucherMessage.classList.remove("text-red-600");
                        voucherDiscount.innerText = `₱${data.discount.toLocaleString(undefined, { minimumFractionDigits: 2 })}`;
                        voucherDetails.classList.remove("hidden");
                    } else {
                        currentDiscount = 0;
                        voucherMessage.textContent = data.message;
                        voucherMessage.classList.add("text-red-600");
                        voucherMessage.classList.remove("text-green-600");
                        voucherDetails.classList.add("hidden");
                    }
                    updateGrandTotal();
                })
                .catch(err => {
                    console.error("Voucher Error:", err);
                    voucherMessage.textContent = "Error validating voucher.";
                    voucherMessage.classList.add("text-red-600");
                })
                .finally(() => submitButton.disabled = false);
        });

        @if(session('message'))
        orderModal.classList.remove("hidden");
        document.getElementById("order-result").innerHTML = `<p class='text-green-500'>{{ session('message') }}</p>`;
        populateOrderData();
        @endif
    });
</script>
@endsection
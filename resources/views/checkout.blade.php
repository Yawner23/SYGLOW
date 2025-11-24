@extends('layouts.main-app')

@section('title', 'Place Order')

@section('content')
<div class="bg-[#fffcf7] h-screen">
    <div class="bg-[#fdebdd] relative z-10">
        <div class="container flex justify-center px-4 py-2 mx-auto space-y-2 text-sm md:flex-row md:space-y-0">
            <img src="{{ asset('images/logo-header.png') }}" alt="Logo">
        </div>
    </div>
    <div class="border-l-2 border-[#f590b0] absolute inset-0 left-1/2 top-0 hidden md:block"></div>
    <div class="container relative z-50 max-w-screen-xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="p-8">
                <div class="space-y-4">
                    <div class="flex flex-row items-center gap-2">
                        <h1 class="text-xl font-bold">Delivery</h1>
                        <button id="change-address-button" class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg w-1/4 py-2 text-white border-2 border-white flex items-center px-2">
                            <i class='bx bx-plus'></i> Change Address
                        </button>
                    </div>

                    <section class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <input type="text" id="full_address" class="w-full" placeholder="Full Address" readonly>
                        </div>
                        <div>
                            <input type="text" id="barangay" class="w-full" placeholder="Barangay" readonly>
                        </div>
                        <div>
                            <input type="text" id="city" class="w-full" placeholder="City/Municipality" readonly>
                        </div>
                        <div>
                            <input type="text" id="province" class="w-full" placeholder="Province" readonly>
                        </div>
                        <div>
                            <input type="text" id="zip_code" class="w-full" placeholder="Zip Code" readonly>
                        </div>
                        <div class="col-span-2">
                            <input type="text" id="email_address" class="w-full" placeholder="Email Address" readonly>
                        </div>
                        <div class="col-span-2">
                            <input type="text" id="delivery_instructions" class="w-full" placeholder="Delivery Instruction (Optional)" readonly>
                        </div>
                    </section>

                    <h1 class="text-xl font-bold">Contact</h1>
                    <section class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <input type="text" id="contact_no" class="w-full" placeholder="Contact No." readonly>
                        </div>
                        <div class="col-span-2">
                            <input type="text" id="tel_no" class="w-full" placeholder="Tel No. (Optional)" readonly>
                        </div>
                    </section>
                </div>

                @include('layouts.delivery_address')
            </div>

            <form action="{{ route('save.payment_summary') }}" method="POST">
                @csrf
                <div class="p-8 space-y-4">

                    @foreach($cart as $id => $product)
                    <div class="flex flex-row space-x-4">
                        <div>
                            <div class="flex justify-end">
                                <button class="bg-[#f590b0] text-white rounded-full w-6 h-6">{{ $product['quantity'] }}</button>
                            </div>
                            <img class="bg-[#fdebdd] object-contain px-8 py-4 h-[8rem] w-full -mt-4" src="{{ asset('images/uploads/product_images/' . $product['image']) }}" alt="{{ $product['name'] }}">
                        </div>
                        <div>
                            <h1 class="text-2xl font-semibold">{{ $product['name'] }}</h1>
                            <p>₱{{ number_format($product['price'], 2) }}</p>
                        </div>
                        <input type="text" name="product_id[]" readonly hidden value="{{ $id }}">
                        <input type="text" name="quantity[]" readonly hidden value="{{ $product['quantity'] }}">
                        <input type="text" name="subtotal[]" readonly hidden value="{{$product['price'] * $product['quantity'] }}">
                    </div>
                    <div class="flex justify-between">
                        <p>Subtotal</p>
                        <p>₱{{ number_format($product['price'] * $product['quantity'], 2) }}</p>
                    </div>
                    @endforeach

                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <p>Total</p>
                            <p>₱{{ number_format($totalAmount , 2) }}</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="voucher_code" class="text-lg font-semibold">Voucher Code (if any)</label>
                        <input type="text" id="voucher_code" name="voucher_code" class="w-full p-2 border rounded" placeholder="Enter voucher code">
                        @error('voucher_code')
                        <div class="mt-2 text-sm text-red-500">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Display voucher discount if available -->
                    @if(session('discount'))
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <p>Voucher Discount</p>
                            <p>₱{{ number_format(session('discount'), 2) }}</p>
                        </div>
                        <div class="flex justify-between font-bold">
                            <p>New Total</p>
                            <p>₱{{ number_format($totalAmount - session('discount'), 2) }}</p>
                        </div>
                    </div>
                    @endif

                    <input type="text" id="customer_id" name="customer_id" value="{{ Auth::user()->id }}" readonly hidden>
                    <input type="text" name="delivery_address_id" id="delivery_address_id" readonly hidden>
                    <input type="text" name="total" id="total" value="{{ $totalAmount }}" readonly hidden>

                    <h1 class="text-2xl font-semibold">Payment Method</h1>
                    <div>
                        <!-- Payment method options -->
                        <div class="mb-[0.125rem] block min-h-[1.5rem] ps-[1.5rem]">
                            <input type="radio" name="payment_method[]" value="card" id="radioDefault01">
                            <label class="mt-px inline-block ps-[0.15rem] hover:cursor-pointer" for="radioDefault01">
                                Credit/Debit Card
                            </label>
                        </div>
                        <div class="mb-[0.125rem] block min-h-[1.5rem] ps-[1.5rem]">
                            <input type="radio" name="payment_method[]" value="gcash" id="radioDefault02">
                            <label class="mt-px inline-block ps-[0.15rem] hover:cursor-pointer" for="radioDefault02">
                                Gcash
                            </label>
                        </div>
                        <div class="mb-[0.125rem] block min-h-[1.5rem] ps-[1.5rem]">
                            <input type="radio" name="payment_method[]" value="maya" id="radioDefault03">
                            <label class="mt-px inline-block ps-[0.15rem] hover:cursor-pointer" for="radioDefault03">
                                Maya
                            </label>
                        </div>
                    </div>

                    @error('payment_method')
                    <div class="mt-2 text-sm text-red-500">{{ $message }}</div>
                    @enderror

                    @if(session('error'))
                    <div class="mt-2 text-sm text-red-500">{{ session('error') }}</div>
                    @endif

                    <div>
                        <button type="submit" class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg w-full py-2 text-white border-2 border-white">
                            PLACE ORDER
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@extends('layouts.sidebar')

@section('title', 'Home')

@section('content')
<div class="flex flex-col justify-between p-8 text-4xl md:flex-row">
    <h1>Payment Summary</h1>
    <h1>Welcome, {{ Auth::user()->distributor->name }}!</h1>
</div>

<div class="container max-w-screen-md py-4 mx-auto md:p-8 ">
    <div class="flex justify-end">
        <button id="paymentSummary_button" class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-l-lg px-12 py-2 text-white">
            PAYMENT SUMMARY
        </button>
        <button id="shipping_button" class="px-12 py-2 border-[#f590b0] border-2 text-black bg-white rounded-r-lg">
            SHIPPING
        </button>
    </div>

    <section id="summary" class="hidden">
        <div class="border bg-[#fdebdd] px-8 pt-4 pb-32 rounded-xl text-black space-y-4 my-8">
            <h1 class="text-2xl font-bold text-center">Payment Summary</h1>
            <div class="hidden border-b border-black border-dashed lg:block"></div>
            <ul class="grid grid-cols-2">
                <li>Order Number:</li>
                <li>#{{$payment->id}}</li>
                @foreach ($payment->products as $product)
                <h1>Ordered Items: </h1>
                <li>{{ $product->product->product_name }} {{ $product->quantity }}x</li>
                <li>Total Amount:</li>
                <li>₱ {{ $product->subtotal}}</li>
                @endforeach
                <li>Payment Method:</li>
                <li>{{$payment->payment_method}}</li>
                <li>Payment Status:</li>
                <li>{{$payment->status}}</li>
                <li>Date of Payment:</li>
                <li>{{$payment->created_at}}</li>
            </ul>
            <div class="hidden border-b border-black border-dashed lg:block"></div>
            <h1 class="text-2xl font-bold text-center">Payment Breakdown</h1>
            <div class="hidden border-b border-black border-dashed lg:block"></div>
            <ul class="grid grid-cols-2">
                <li>Bundled Items Total:</li>
                <li>₱ {{$payment->total}}</li>

            </ul>
            <div class="hidden border-b border-black border-dashed lg:block"></div>
        </div>
        <div class="relative">
            <!-- Hidden file input -->
            <input type="file" id="upload_payment" name="upload_payment" class="absolute inset-0 hidden cursor-pointer" />

            <!-- Styled button -->
            <button type="button" id="uploadButton" class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg px-12 py-2 text-white 
 ">
                UPLOAD PAYMENT
            </button>
        </div>
    </section>

    <section id="shipping" class="hidden">
        <div class="border bg-[#fdebdd] px-8 pt-4 pb-20 rounded-xl text-black space-y-4 my-8">
            <h1 class="text-2xl font-bold text-center">SHIPPING</h1>
            <div class="hidden border-b border-black border-dashed lg:block"></div>
            <ul class="grid grid-cols-2">
                <li>Courier:</li>
                <li>#{{ $payment->shipping->id ?? 'N/A' }}</li>
                <li>Shipping Fee:</li>
                <li>₱ {{ $payment->shipping->shipping_fee ?? 'N/A' }}</li>
                <li>Date of Shipping:</li>
                <li>{{ $payment->shipping->created_at ?? 'N/A' }}</li>
            </ul>
            <div class="hidden border-b border-black border-dashed lg:block"></div>
            <ul class="grid grid-cols-2">
                <li>Total:</li>
                <li>
                    @if($payment->shipping && $payment->shipping->shipping_fee !== null)
                    ₱ {{ $payment->total + $payment->shipping->shipping_fee }}
                    @else
                    ₱ {{ $payment->total }}
                    @endif
                </li>
            </ul>
            <div class="hidden border-b border-black border-dashed lg:block"></div>
        </div>
        <div class="relative">
            <!-- Hidden file input -->
            <input type="file" id="upload_shipping_payment" name="upload_shipping_payment" class="absolute inset-0 opacity-0 cursor-pointer"
                @if(!$payment->shipping || !$payment->shipping->id && !$payment->shipping->shipping_fee && !$payment->shipping->created_at) disabled @endif />

            <!-- Styled button -->
            <button type="button" id="uploadButton2" class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg px-12 py-2 text-white 
               @if(!$payment->shipping || !$payment->shipping->id && !$payment->shipping->shipping_fee && !$payment->shipping->created_at) opacity-50 cursor-not-allowed @endif">
                UPLOAD SHIPPING PAYMENT
            </button>
        </div>
    </section>

    <div>

    </div>

    <script>
        const paymentSummaryButton = document.getElementById('paymentSummary_button');
        const shippingButton = document.getElementById('shipping_button');
        const summarySection = document.getElementById('summary');
        const shippingSection = document.getElementById('shipping');

        function toggleSections(showSection, hideSection) {
            showSection.classList.remove('hidden');
            hideSection.classList.add('hidden');
        }

        function toggleButtons(activeButton, inactiveButton) {
            activeButton.classList.remove('text-black', 'bg-white');
            activeButton.classList.add('bg-gradient-to-r', 'from-[#f590b0]', 'to-[#f56e98]', 'text-white');

            inactiveButton.classList.remove('bg-gradient-to-r', 'from-[#f590b0]', 'to-[#f56e98]', 'text-white');
            inactiveButton.classList.add('text-black', 'bg-white', 'border-2', 'border-[#f590b0]');
        }

        paymentSummaryButton.addEventListener('click', () => {
            toggleSections(summarySection, shippingSection);
            toggleButtons(paymentSummaryButton, shippingButton);
        });

        shippingButton.addEventListener('click', () => {
            toggleSections(shippingSection, summarySection);
            toggleButtons(shippingButton, paymentSummaryButton);
        });

        // Initially show the Payment Summary section
        toggleSections(summarySection, shippingSection);
        toggleButtons(paymentSummaryButton, shippingButton);
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#uploadButton').on('click', function() {
                $('#upload_payment').click();
            });
            $('#uploadButton2').on('click', function() {
                $('#upload_shipping_payment').click();
            });
            $('#upload_payment').on('change', function() {
                var fileInput = $(this)[0];
                var file = fileInput.files[0];

                if (file) {
                    var formData = new FormData();
                    formData.append('upload_payment', file);
                    formData.append('payment_id', '{{ $payment->id }}'); // Pass the payment ID
                    formData.append('_token', '{{ csrf_token() }}'); // CSRF token for security

                    $.ajax({
                        url: "{{ route('payments.uploadPayment') }}",
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                // Update the date_of_payment to now
                                $.ajax({
                                    url: "{{ route('payments.updateDate') }}",
                                    type: 'POST',
                                    data: {
                                        payment_id: '{{ $payment->id }}',
                                        date_of_payment: new Date().toISOString(),
                                        _token: '{{ csrf_token() }}'
                                    },
                                    success: function() {
                                        alert('Payment uploaded and date updated successfully');
                                    },
                                    error: function() {
                                        alert('Error updating date_of_payment');
                                    }
                                });
                            } else {
                                alert('Error uploading payment');
                            }
                        },
                        error: function() {
                            alert('Error uploading payment');
                        }
                    });
                }
            });


            $('#upload_shipping_payment').on('change', function() {
                var fileInput = $(this)[0];
                var file = fileInput.files[0];

                if (file) {
                    var formData = new FormData();
                    formData.append('upload_shipping_payment', file);
                    formData.append('payment_id', '{{ $payment->id }}'); // Pass the payment ID
                    formData.append('_token', '{{ csrf_token() }}'); // CSRF token for security

                    $.ajax({
                        url: "{{ route('payments.uploadshippingPayment') }}",
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                // Update the date_of_payment to now
                                $.ajax({
                                    url: "{{ route('payments.updateDate') }}",
                                    type: 'POST',
                                    data: {
                                        payment_id: '{{ $payment->id }}',
                                        date_of_payment: new Date().toISOString(),
                                        _token: '{{ csrf_token() }}'
                                    },
                                    success: function() {
                                        alert('Payment uploaded and date updated successfully');
                                    },
                                    error: function() {
                                        alert('Error updating date_of_payment');
                                    }
                                });
                            } else {
                                alert('Error uploading payment');
                            }
                        },
                        error: function() {
                            alert('Error uploading payment');
                        }
                    });
                }
            });
        });
    </script>

</div>
@endsection
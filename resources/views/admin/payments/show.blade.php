<x-app-layout>
    <div class="container max-w-screen-md p-6 mx-auto bg-white rounded-lg shadow-md">
        <div class="mb-4 text-center">
            <h1 class="text-3xl font-bold">Payment Details!</h1>
        </div>

        <div class="py-2 border-t border-gray-200">
            <p class="text-sm font-semibold">Receipt ID:</p>
            <p class="text-lg font-bold text-gray-800">{{ $payment->id }}</p>
        </div>

        <div class="py-2 border-t border-gray-200">
            <p class="text-sm font-semibold">Tracking Number:</p>
            <p class="text-lg font-bold text-gray-800">{{ $payment->tracking_number }}</p>
        </div>

        <div class="py-2 border-t border-gray-200">
            <p class="text-sm font-semibold">Customer:</p>
            <p class="text-lg text-gray-800">{{ $payment->customer->name }}</p>
        </div>

        <div class="py-2 border-t border-gray-200">
            <p class="text-sm font-semibold">Delivery Address:</p>
            <p class="text-lg text-gray-800">{{ $payment->deliveryAddress->full_address }}</p>
        </div>

        <div class="py-2 border-t border-gray-200">
            <p class="text-sm font-semibold">Items Purchased:</p>
            <ul class="pl-5 list-disc">
                @foreach ($payment->products as $product)
                <li class="py-1">
                    <p class="text-sm font-medium">{{ $product->product->product_name }}</p>
                    <p class="text-sm text-gray-600">Subtotal: ₱{{ $product->subtotal }}</p>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="py-2 border-t border-gray-200">
            <p class="text-sm font-semibold">Shipping Fee:</p>
            <p class="text-lg text-gray-800">₱{{ $payment->shipping->shipping_fee ?? '0.00' }}</p>
        </div>

        <div class="py-2 border-t border-gray-200">
            <p class="text-sm font-semibold">Total:</p>
            <p class="text-lg font-bold text-gray-800">₱{{ $payment->total }}</p>
        </div>

        <div class="py-2 border-t border-gray-200">
            <p class="text-sm font-semibold">Status:</p>
            <p class="text-lg text-gray-800">{{ $payment->status }}</p>
        </div>

        <div class="py-2 border-t border-gray-200">
            <p class="text-sm font-semibold">Uploaded Payment:</p>
            @if($payment->upload_payment)
            <a href="{{ asset('/uploads/payments/' . $payment->upload_payment) }}" target="_blank" class="text-blue-500 hover:underline">
                View File
            </a>
            @else
            <p class="text-lg text-gray-800">No file uploaded</p>
            @endif
        </div>
        <div class="py-2 border-t border-gray-200">
            <p class="text-sm font-semibold">Uploaded Shipping Payment:</p>
            @if($payment->upload_shipping_payment)
            <a href="{{ asset('/uploads/payments/' . $payment->upload_shipping_payment) }}" target="_blank" class="text-blue-500 hover:underline">
                View File
            </a>
            @else
            <p class="text-lg text-gray-800">No file uploaded</p>
            @endif
        </div>
    </div>
</x-app-layout>
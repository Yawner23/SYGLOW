<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Shipping;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function create(Payment $payment)
    {
        return view('admin.shipping.create', compact('payment'));
    }

    public function store(Request $request, Payment $payment)
    {
        $request->validate([
            'courier' => 'nullable|string',
            'date_of_shipping' => 'nullable|string',
            'shipping_fee' => 'nullable|string',
        ]);

        Shipping::updateOrCreate(
            ['payment_id' => $payment->id],
            $request->only('courier', 'date_of_shipping', 'shipping_fee')
        );

        return redirect()->route('payments.show', $payment)->with('success', 'Shipping information updated successfully.');
    }
}

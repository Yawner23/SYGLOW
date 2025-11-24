<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryAddress;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function destroy($id)
    {
        $address = DeliveryAddress::findOrFail($id);
        $address->delete();

        return redirect()->back()->with('success', 'Address deleted successfully.');
    }
    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'deliver_name' => 'nullable|string|max:255',
            'full_address' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:10',
            'email_address' => 'nullable|email|max:255',
            'delivery_instructions' => 'nullable|string|max:255',
            'contact_no' => 'nullable|string|max:255',
            'tel_no' => 'nullable|string|max:255',
            'id' => 'nullable|exists:delivery_address,id', // Optional: for updating existing address
        ]);

        // Check if an ID is present to determine whether to update or create
        if (isset($validatedData['id'])) {
            // Update existing delivery address
            $deliveryAddress = DeliveryAddress::findOrFail($validatedData['id']);
            $deliveryAddress->update($validatedData);
        } else {
            // Create a new delivery address
            $deliveryAddress = new DeliveryAddress($validatedData);
            $deliveryAddress->save();
        }

        // Redirect or return a response
        return redirect()->back()->with('success', 'Delivery Address saved successfully!');
    }
}

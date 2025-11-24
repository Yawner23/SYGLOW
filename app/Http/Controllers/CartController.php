<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function showCheckout()
    {
        $cart = session('cart', []);

        // Calculate total amount and shipping (if any)
        $totalAmount = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));


        $user = Auth::user();
        $delivery_address = $user->delivery_address;

        return view('checkout', compact(
            'cart',
            'totalAmount',
            'delivery_address'
        ));
    }

    public function add(Request $request, Product $product)
    {

        // Assuming you have a cart session or a Cart model to manage the user's cart
        $cart = session()->get('cart', []);

        $product_price = $request->input('product_price');

        // Add the product to the cart (you may want to add more logic, such as quantities, etc.)
        $cart[$product->id] = [
            "name" => $product->product_name,
            "quantity" => 1,
            "price" => $product_price,
            "image" => $product->images->first()->image_path ?? 'default.png'
        ];

        session()->put('cart', $cart);

        // Redirect to the distributor_cart page
        return redirect()->route('distributor_cart');
    }

    public function viewCart()
    {
        $cart = session()->get('cart', []);
        return view('distributor_cart', compact('cart'));
    }

    public function saveCart(Request $request)
    {
        $cartProducts = $request->input('cartProducts');
        session(['cartProducts' => $cartProducts]);

        return response()->json(['success' => true]);
    }
    public function showCheckoutPage()
    {
        // Retrieve cart products from the session
        $cartProducts = session()->get('cartProducts', []);

        // Initialize subtotal
        $subtotal = 0;

        // Calculate subtotal for each product
        foreach ($cartProducts as &$product) {
            // Use discount_price if available, otherwise use regular price
            $effectivePrice = isset($product['discount_price']) && $product['discount_price'] > 0
                ? $product['discount_price']
                : $product['price'];

            $product['subtotal'] = $effectivePrice * $product['quantity'];
            $subtotal += $product['subtotal'];
        }

        // Calculate shipping cost (example value)
        $shipping = 0; // Adjust as needed

        // Calculate the total amount
        $totalAmount = $subtotal + $shipping;

        $user = Auth::user();
        $delivery_address = $user->delivery_address;

        // Pass cart products, subtotal, shipping, and total amount to the view
        return view('place_order', compact('cartProducts', 'subtotal', 'shipping', 'totalAmount', 'delivery_address'));
    }


    public function remove($id)
    {
        $cart = session()->get('cart');

        // Check if the item exists in the cart
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        // Redirect back to the cart page
        return redirect()->route('distributor_cart')->with('success', 'Product removed successfully.');
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart');

        // Check if the item exists in the cart
        if (isset($cart[$id])) {
            if ($request->action === 'increase') {
                $cart[$id]['quantity']++;
            } elseif ($request->action === 'decrease') {
                if ($cart[$id]['quantity'] > 1) {
                    $cart[$id]['quantity']--;
                } else {
                    return redirect()->route('distributor_cart')->with('error', 'Quantity cannot be less than 1.');
                }
            }
            session()->put('cart', $cart);
        }

        return redirect()->route('distributor_cart')->with('success', 'Cart updated successfully.');
    }
}

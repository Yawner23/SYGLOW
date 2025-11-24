<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function store($product_id, $customer_id, Request $request)
    {


        // Check if the product is already in the user's wishlist
        $existingWishlistItem = Wishlist::where('product_id', $product_id)
            ->where('customer_id', $customer_id)
            ->first();

        if ($existingWishlistItem) {
            // Optionally handle the case where the item already exists in the wishlist
            return redirect()->route('view.wishlist')->with('status', 'Product is already in your wishlist.');
        }

        // Create a new wishlist item
        Wishlist::create([
            'product_id' => $product_id,
            'customer_id' => $customer_id,
        ]);
        return redirect()->route('view.wishlist')->with('status', 'Product added to your wishlist.');
    }

    public function destroy($id)
    {
        $wishlist = Wishlist::findOrFail($id);
        $wishlist->delete();

        return redirect()->route('view.wishlist')->with('success', 'Item removed from wishlist.');
    }
}

<?php

namespace App\Http\Controllers;

use Storage;
use App\Models\Benefit;
use App\Models\Product;
use App\Models\Category;
use App\Models\Consumer;
use App\Models\PackType;
use App\Models\ProductBenefit;
use App\Models\ProductDimension;
use App\Models\SkinType;
use App\Models\ProductType;
use App\Models\ProductImage;
use App\Models\ProductPrice;
use App\Models\ProductSkinType;
use Illuminate\Http\Request;
use App\Models\ProductWeight;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{

    public function updateStatus(Request $request)
    {
        $product = Product::find($request->id);
        if ($product) {
            $product->status = $request->status;
            $product->save();
        }

        return response()->json(['success' => true]);
    }

    public function index()
    {
        if (request()->ajax()) {
            $products = Product::query();
            return DataTables::of($products)

                ->addIndexColumn()
                ->addColumn('product_type', function ($product) {
                    return $product->productType ? $product->productType->product_type : 'N/A';
                })
                ->addColumn('status', function ($product) {
                    $statusText = ucfirst(str_replace('_', ' ', $product->status));
                    $checked = $product->status === 'new_arrival' ? 'checked' : '';
                    return '<input type="checkbox" class="status-checkbox" data-id="' . $product->id . '" ' . $checked . '> ' . $statusText;
                })
                ->addColumn('action', function ($product) {
                    return '<a href="' . route('products.edit', $product->id) . '" class="text-black"><i class="text-4xl bx bx-edit"></i></a>
                            <form action="' . route('products.destroy', $product->id) . '" method="POST" style="display: inline-block;">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="text-red-500"><i class="text-4xl bx bx-trash"></i></button>
                            </form>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('admin.products.index');
    }

    // Show the form for creating a new category, product type, or product
    public function create()
    {
        $product_types = ProductType::all();
        $consumers = Consumer::all();
        $skin_type = SkinType::all();
        $benefits = Benefit::all();
        return view('admin.products.create', compact('product_types', 'consumers', 'skin_type', 'benefits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_type_id' => 'required|exists:product_types,id',
            'shelf_life' => 'required|string|max:255',
            'volume' => 'required|string|max:255',
            'edition' => 'required|string|in:Regular,Limited',
            'product_form' => 'required|string|max:255',
            'quantity_per_pack' => 'required|numeric',
            'pack_type' => 'required|string|in:Single item,Multi pack',
            'product_description' => 'required|string',
            'prices' => 'array',
            'prices.*' => 'nullable|numeric|min:0',
            'quantity' => 'required|numeric|min:1',
            'seller_sku' => 'required|string|max:255',
            'weights' => 'array',
            'weights.*' => 'nullable|numeric|min:0',
            'weight_unit' => 'array',
            'weight_unit.*' => 'nullable|string',
            'length_cm' => 'required|numeric|min:0',
            'width_cm' => 'required|numeric|min:0',
            'height_cm' => 'required|numeric|min:0',
            'availability' => 'required|string|in:in_stock,out_of_stock',
            'status' => 'required|string|in:new_arrival',
            'image_path.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Create the product
        $product = Product::create([
            'product_type_id' => $request->input('product_type_id'),
            'product_name' => $request->input('product_name'),
            'shelf_life' => $request->input('shelf_life'),
            'volume' => $request->input('volume'),
            'edition' => $request->input('edition'),
            'product_form' => $request->input('product_form'),
            'quantity' => $request->input('quantity'),
            'seller_sku' => $request->input('seller_sku'),
            'product_description' => $request->input('product_description'),
            'availability' => $request->input('availability'),
            'status' => $request->input('status'),
        ]);
        // Save pack_type
        PackType::create([
            'product_id' => $product->id,
            'pack_type' => $request->input('pack_type'),
            'quantity_per_pack' => $request->input('quantity_per_pack'),
        ]);

        // Save Skin Type 
        if ($request->has('skin_type_id')) {
            $skinTypeIds = $request->input('skin_type_id');

            // Ensure $skinTypeIds is an array
            if (is_array($skinTypeIds)) {
                foreach ($skinTypeIds as $skin_type_id) {
                    // Validate and convert to integer
                    $skin_type_id = intval($skin_type_id);

                    // Ensure the value is a positive integer
                    if ($skin_type_id > 0) {
                        ProductSkinType::create([
                            'product_id' => $product->id,
                            'skin_type_id' => $skin_type_id,
                        ]);
                    }
                }
            }
        }

        if ($request->has('skin_type_id')) {
            foreach ($request->input('skin_type_id') as $skin_type_id => $value) {
                ProductSkinType::create([
                    'product_id' => $product->id,
                    'skin_type_id' => $skin_type_id,
                ]);
            }
        }

        // Save Skin Type 
        if ($request->has('benefit_id')) {
            foreach ($request->input('benefit_id') as $benefit_id => $value) {
                ProductBenefit::create([
                    'product_id' => $product->id,
                    'benefit_id' => $benefit_id,
                ]);
            }
        }


        if ($request->has('weights')) {
            $weights = $request->input('weights');
            $weightUnits = $request->input('weight_unit');

            // Ensure $weights and $weightUnits are arrays
            if (is_array($weights) && is_array($weightUnits)) {
                foreach ($weights as $index => $weight) {
                    // Convert $weight to a string or numeric value
                    $weight = is_numeric($weight) ? (float)$weight : (string)$weight;

                    // Ensure $weightUnits has a corresponding value
                    $weightUnit = isset($weightUnits[$index]) ? (string)$weightUnits[$index] : '';

                    ProductWeight::create([
                        'product_id' => $product->id,
                        'weights' => $weight,
                        'weight_unit' => $weightUnit,
                    ]);
                }
            }
        }


        ProductDimension::create([
            'product_id' => $product->id,
            'length_cm' => $request->input('length_cm'),
            'width_cm' => $request->input('width_cm'),
            'height_cm' => $request->input('height_cm'),
        ]);

        // Save images
        if ($request->hasFile('image_path')) {
            foreach ($request->file('image_path') as $image_path) {
                $imageName = time() . '_' . $image_path->getClientOriginalName();
                $image_path->move(public_path('images/uploads/product_images'), $imageName);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imageName,
                ]);
            }
        }

        // Save prices for each selected consumer
        if ($request->has('consumers')) {
            foreach ($request->input('consumers') as $consumerId => $value) {
                if (!empty($request->input('prices.' . $consumerId))) {
                    ProductPrice::create([
                        'product_id' => $product->id,
                        'consumer_id' => $consumerId,
                        'price' => $request->input('prices.' . $consumerId),
                    ]);
                }
            }
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }


    public function edit($id)
    {
        // Retrieve the product with related weights, images, skinTypes, benefits, and dimensions
        $product = Product::with(['weights', 'images', 'skinTypes', 'benefits', 'dimensions'])
            ->findOrFail($id);

        // Retrieve all product types and consumers
        $product_types = ProductType::all();
        $consumers = Consumer::all();

        // Retrieve product prices associated with the product
        $product_prices = ProductPrice::where('product_id', $id)->get();

        // Pluck prices by consumer_id
        $prices = $product_prices->pluck('price', 'consumer_id')->toArray();

        // ⭐ Pluck discount prices by consumer_id
        $discounts = $product_prices->pluck('discount_price', 'consumer_id')->toArray();

        // Retrieve all skin types and benefits for the form
        $skin_types = SkinType::all();
        $benefits = Benefit::all();

        // Prepare selected skin types and benefits for the view
        $selected_skin_types = $product->skinTypes->pluck('id')->toArray();
        $selected_benefits = $product->benefits->pluck('id')->toArray();

        return view('admin.products.edit', compact('product', 'product_types', 'consumers', 'prices', 'discounts', 'skin_types', 'benefits', 'selected_skin_types', 'selected_benefits'));
    }




    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_type_id' => 'required|exists:product_types,id',
            'product_name' => 'required|string|max:255',
            'shelf_life' => 'required|string|max:255',
            'volume' => 'required|string|max:255',
            'edition' => 'required|string|in:Regular,Limited',
            'product_form' => 'required|string|max:255',
            'quantity_per_pack' => 'required|numeric',
            'pack_type' => 'required|string|in:Single item,Multi pack',
            'product_description' => 'nullable|string',
            'quantity' => 'required|numeric|min:1',
            'seller_sku' => 'required|string|max:255',
            'weights' => 'array',
            'weights.*' => 'nullable|numeric|min:0',
            'weight_unit' => 'array',
            'weight_unit.*' => 'nullable|string',
            'length_cm' => 'required|numeric|min:0',
            'width_cm' => 'required|numeric|min:0',
            'height_cm' => 'required|numeric|min:0',
            'availability' => 'required|string|in:in_stock,out_of_stock',

            'image_path.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'consumers' => 'nullable|array',
            'prices' => 'nullable|array',
            'prices.*' => 'nullable|numeric|min:0',
            'discount_price' => 'nullable|array',
            'discount_price.*' => 'nullable|numeric|min:0',
        ]);

        // Update product details
        $product->update([
            'product_type_id' => $request->input('product_type_id'),
            'product_name' => $request->input('product_name'),
            'shelf_life' => $request->input('shelf_life'),
            'volume' => $request->input('volume'),
            'edition' => $request->input('edition'),
            'product_form' => $request->input('product_form'),
            'quantity' => $request->input('quantity'),
            'seller_sku' => $request->input('seller_sku'),
            'product_description' => $request->input('product_description'),
            'availability' => $request->input('availability'),

        ]);

        // Update or create PackType
        $packType = PackType::where('product_id', $product->id)->first();
        if ($packType) {
            $packType->update([
                'pack_type' => $request->input('pack_type'),
                'quantity_per_pack' => $request->input('quantity_per_pack'),
            ]);
        } else {
            PackType::create([
                'product_id' => $product->id,
                'pack_type' => $request->input('pack_type'),
                'quantity_per_pack' => $request->input('quantity_per_pack'),
            ]);
        }

        ProductSkinType::where('product_id', $product->id)->delete();
        if ($request->has('skin_type_id')) {
            foreach ($request->input('skin_type_id') as $skin_type_id => $value) {
                if ($value) {
                    ProductSkinType::create([
                        'product_id' => $product->id,
                        'skin_type_id' => $skin_type_id,
                    ]);
                }
            }
        }

        // Update benefits
        ProductBenefit::where('product_id', $product->id)->delete();
        if ($request->has('benefit_id')) {
            foreach ($request->input('benefit_id') as $benefit_id => $value) {
                if ($value) {
                    ProductBenefit::create([
                        'product_id' => $product->id,
                        'benefit_id' => $benefit_id,
                    ]);
                }
            }
        }


        // Update or create ProductWeight
        if ($request->has('weights')) {
            $weights = $request->input('weights');
            $weightUnits = $request->input('weight_unit');

            // Detach old weights
            ProductWeight::where('product_id', $product->id)->delete();

            if (is_array($weights) && is_array($weightUnits)) {
                foreach ($weights as $index => $weight) {
                    $weight = is_numeric($weight) ? (float)$weight : (string)$weight;
                    $weightUnit = isset($weightUnits[$index]) ? (string)$weightUnits[$index] : '';
                    ProductWeight::create([
                        'product_id' => $product->id,
                        'weights' => $weight,
                        'weight_unit' => $weightUnit,
                    ]);
                }
            }
        }

        // Update or create ProductDimension
        $dimension = ProductDimension::where('product_id', $product->id)->first();
        if ($dimension) {
            $dimension->update([
                'length_cm' => $request->input('length_cm'),
                'width_cm' => $request->input('width_cm'),
                'height_cm' => $request->input('height_cm'),
            ]);
        } else {
            ProductDimension::create([
                'product_id' => $product->id,
                'length_cm' => $request->input('length_cm'),
                'width_cm' => $request->input('width_cm'),
                'height_cm' => $request->input('height_cm'),
            ]);
        }

        // Handle new images
        if ($request->hasFile('image_path') && count($request->file('image_path')) > 0) {
            // Delete existing images
            $existingImages = $product->images; // Assuming 'images' is the relationship name
            foreach ($existingImages as $image) {
                $imagePath = public_path('images/uploads/product_images/' . $image->image_path);
                if (file_exists($imagePath)) {
                    unlink($imagePath); // Delete the image file
                }
                $image->delete(); // Remove image record from the database
            }

            // Upload new images
            foreach ($request->file('image_path') as $image_path) {
                $imageName = time() . '_' . $image_path->getClientOriginalName();
                $image_path->move(public_path('images/uploads/product_images'), $imageName);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imageName,
                ]);
            }
        }

        // Update consumer prices
        // Update consumer prices
        ProductPrice::where('product_id', $product->id)->delete();

        Log::info("💵 Clearing old prices for product {$product->id}");

        if ($request->has('consumers')) {
            foreach ($request->input('consumers') as $consumerId => $value) {

                $price = $request->input('prices.' . $consumerId);
                if ($price === null || $price === '') {
                    Log::warning("⚠️ Price missing for consumer {$consumerId}, skipping...");
                    continue;
                }

                // Customer only discount (ID = 5)
                $discountPrice = null;
                if ($consumerId == 5) {
                    $discountPrice = $request->input('discount_price.' . $consumerId);
                }

                ProductPrice::create([
                    'product_id' => $product->id,
                    'consumer_id' => $consumerId,
                    'price' => $price,
                    'discount_price' => $discountPrice,
                ]);

                Log::info("💰 Updated price for consumer {$consumerId}", [
                    'price' => $price,
                    'discount_price' => $discountPrice,
                ]);
            }
        }


        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }




    public function destroy($id)
    {
        $products = Product::findOrFail($id);
        $products->delete();

        return redirect()->route('products.index');
    }
}

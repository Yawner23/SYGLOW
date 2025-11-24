<x-app-layout>
    <div class="container max-w-lg p-6 mx-auto bg-white rounded-lg shadow-lg">
        <h1 class="mb-6 text-3xl font-extrabold text-gray-800">Update Product</h1>

        @if ($errors->any())
        <div class="p-4 mb-4 text-white bg-red-500 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')



            <!-- Product Name -->
            <div class="mb-4">
                <label for="product_name" class="block text-sm font-medium text-gray-700">Product Name</label>
                <input type="text" name="product_name" id="product_name" value="{{ old('product_name', $product->product_name) }}" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg" required>
                @error('product_name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Product Type -->
            <div class="mb-4">
                <label for="product_type_id" class="block text-sm font-medium text-gray-700">Product Type</label>
                <select id="product_type_id" name="product_type_id" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg">
                    <option value="">Select Product Type</option>
                    @foreach($product_types as $product_type)
                    <option value="{{ $product_type->id }}" {{ $product->product_type_id == $product_type->id ? 'selected' : '' }}>
                        {{ $product_type->product_type }}
                    </option>
                    @endforeach
                </select>
                @error('product_type_id')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Shelf Life Field -->
            <div class="mb-4">
                <label for="shelf_life" class="block text-sm font-medium text-gray-700">Shelf Life</label>
                <input type="text" name="shelf_life" id="shelf_life" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg" required value="{{ old('shelf_life',$product->shelf_life) }}">
                @error('shelf_life')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Volume Field -->
            <div class="mb-4">
                <label for="volume" class="block text-sm font-medium text-gray-700">Volume</label>
                <input type="text" name="volume" id="volume" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg" required value="{{ old('volume', $product->volume) }}">
                @error('volume')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Product Edition Field -->
            <div class="mb-4">
                <label for="edition" class="block text-sm font-medium text-gray-700">Product Edition</label>
                <select id="edition" name="edition" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg" required>
                    <option value="">Select Product Edition</option>
                    <option value="Regular" {{ $product->edition == 'Regular' ? 'selected' : '' }}>Regular Edition</option>
                    <option value="Limited" {{ $product->edition == 'Limited' ? 'selected' : '' }}>Limited Edition</option>
                </select>
                @error('edition')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <!-- Product Form Field -->
            <div class="mb-4">
                <label for="product_form" class="block text-sm font-medium text-gray-700">Product Form</label>
                <input type="text" name="product_form" id="product_form" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg" required value="{{ old('product_form', $product->product_form) }}">
                @error('product_form')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Quantity Per Pack Field -->
                <div class="mb-4">
                    <label for="quantity_per_pack" class="block text-sm font-medium text-gray-700">Quantity Per Pack</label>
                    <input type="text" name="quantity_per_pack" id="quantity_per_pack" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg" required value="{{ old('quantity_per_pack', $product->packTypes->quantity_per_pack) }}">
                    @error('quantity_per_pack')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Pack Type Field -->
                <div class="mb-4">
                    <label for="pack_type" class="block text-sm font-medium text-gray-700">Pack Type</label>
                    <select id="pack_type" name="pack_type" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg" required>
                        <option value="">Select Pack Type</option>
                        <option value="Single item" {{  $product->packTypes->pack_type == 'Single item' ? 'selected' : '' }}>Single Item</option>
                        <option value="Multi pack" {{  $product->packTypes->pack_type == 'Multi pack' ? 'selected' : '' }}>Multi Pack</option>
                    </select>
                    @error('pack_type')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Pack Type Field -->
            <!-- Skin Types -->
            <div class="mb-4">
                <label for="skin_type_id" class="block text-sm font-medium text-gray-700">Skin Types</label>
                <div id="skin_type_id">
                    @foreach($skin_types as $skin_type)
                    <div class="grid items-center grid-cols-2 mb-2 space-x-2">
                        <div class="flex items-center">
                            <input type="checkbox" name="skin_type_id[{{ $skin_type->id }}]" id="skin_type_id{{ $skin_type->id }}" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" {{ in_array($skin_type->id, $selected_skin_types) ? 'checked' : '' }}>
                            <label for="skin_type_id{{ $skin_type->id }}" class="block ml-2 text-sm font-medium text-gray-700">{{ $skin_type->skin_type }}</label>
                        </div>
                    </div>
                    @endforeach
                </div>
                @error('skin_types')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>



            <!-- Benefits -->
            <div class="mb-4">
                <label for="benefit_id" class="block text-sm font-medium text-gray-700">Benefits</label>
                <div id="benefit_id">
                    @foreach($benefits as $benefit)
                    <div class="grid items-center grid-cols-2 mb-2 space-x-2">
                        <div class="flex items-center">
                            <input type="checkbox" name="benefit_id[{{ $benefit->id }}]" id="benefit_id{{ $benefit->id }}" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" {{ in_array($benefit->id, $selected_benefits) ? 'checked' : '' }}>
                            <label for="benefit_id{{ $benefit->id }}" class="block ml-2 text-sm font-medium text-gray-700">{{ $benefit->benefit_name }}</label>
                        </div>
                    </div>
                    @endforeach
                </div>
                @error('benefits')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Product Description -->
            <div class="mb-4">
                <label for="product_description" class="block text-sm font-medium text-gray-700">Product Description</label>
                <textarea rows="4" name="product_description" id="product_description" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg" required>{{ old('product_description', $product->product_description) }}</textarea>
                @error('product_description')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>

            <script>
                CKEDITOR.replace('product_description');
            </script>




            <!-- Prices by Consumer -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Prices by Consumer</label>
                <div id="consumer-prices">
                    @foreach($consumers as $consumer)
                    <div class="mb-4 p-3 border rounded-lg bg-white">
                        <div class="grid items-center grid-cols-2 mb-2 space-x-2">

                            <!-- Checkbox -->
                            <div class="flex items-center">
                                <input type="checkbox"
                                    name="consumers[{{ $consumer->id }}]"
                                    id="consumer_{{ $consumer->id }}"
                                    class="w-4 h-4 text-indigo-600 rounded"
                                    {{ isset($prices[$consumer->id]) ? 'checked' : '' }}>
                                <label for="consumer_{{ $consumer->id }}" class="ml-2 text-sm font-medium text-gray-700">
                                    {{ $consumer->consumer_name }}
                                </label>
                            </div>

                            <!-- Price -->
                            <input type="number"
                                step="0.01"
                                name="prices[{{ $consumer->id }}]"
                                id="price_{{ $consumer->id }}"
                                class="p-2 border rounded-lg w-full"
                                placeholder="Price"
                                value="{{ old('prices.'.$consumer->id, $prices[$consumer->id] ?? '') }}">
                        </div>

                        <!-- Discount only for customer -->
                        @if($consumer->id == 5)
                        <div class="mt-2 ml-6">

                            <!-- Discount Checkbox -->
                            <div class="flex items-center mb-2">
                                <input type="checkbox"
                                    name="enable_discount[5]"
                                    id="enable_discount_5"
                                    class="w-4 h-4 text-green-600 rounded"
                                    {{ !empty($discounts[5] ?? null) ? 'checked' : '' }}>
                                <label for="enable_discount_5" class="ml-2 text-sm text-gray-700">
                                    Enable Discount Price (Customer Only)
                                </label>
                            </div>

                            <!-- Discount Input -->
                            <input type="number"
                                step="0.01"
                                name="discount_price[5]"
                                id="discount_price_5"
                                class="p-2 border rounded-lg w-full"
                                placeholder="Discount Price"
                                value="{{ old('discount_price.5', $discounts[5] ?? '') }}">
                        </div>
                        @endif


                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Volume Field -->
            <div class="mb-4">
                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                <input type="text" name="quantity" id="quantity" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg" required value="{{ old('quantity', $product->quantity) }}">
                @error('quantity')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Quantity Field -->
            <div class="mb-4">
                <label for="seller_sku" class="block text-sm font-medium text-gray-700">Seller SKU</label>
                <input type="text" name="seller_sku" id="seller_sku" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg" required value="{{ old('seller_sku' , $product->seller_sku) }}">
                @error('seller_sku')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Weights -->
            <div class="mb-4">
                <div id="weights-container" class="grid grid-cols-2 gap-4">
                    <div class="weight-item">
                        <label for="weights" class="block text-sm font-medium text-gray-700">Weights</label>
                        @foreach($product->weights as $weight)
                        <input
                            type="number"
                            name="weights[]"
                            value="{{ old('weights.' . $loop->index, $weight->weights) }}"
                            class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg"
                            required>
                        @endforeach
                    </div>

                    <div class="weight-unit-item">
                        <label for="weight_unit" class="block text-sm font-medium text-gray-700">Weight Unit</label>
                        @foreach($product->weights as $weight_unit)
                        <select
                            name="weight_unit[]"
                            class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg"
                            required>
                            <option value="">Select Weight Unit</option>
                            <option value="Grams" {{ $weight_unit->weight_unit == 'Grams' ? 'selected' : '' }}>Grams</option>
                            <option value="Kilograms" {{ $weight_unit->weight_unit == 'Kilograms' ? 'selected' : '' }}>Kilograms</option>
                        </select>
                        @endforeach
                    </div>
                </div>

                @error('weights')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>


            <!-- Quantity Field -->
            <div class="grid grid-cols-3 gap-4">
                <div class="mb-4">
                    <label for="length_cm" class="block text-sm font-medium text-gray-700">Length</label>
                    <input type="number" name="length_cm" id="length_cm" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg" required value="{{ old('length_cm' , $product->dimensions->length_cm) }}">
                    @error('length_cm')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="width_cm" class="block text-sm font-medium text-gray-700">Width</label>
                    <input type="number" name="width_cm" id="width_cm" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg" required value="{{ old('width_cm' , $product->dimensions->width_cm) }}">
                    @error('width_cm')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="height_cm" class="block text-sm font-medium text-gray-700">Height</label>
                    <input type="number" name="height_cm" id="height_cm" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg" required value="{{ old('height_cm' , $product->dimensions->height_cm) }}">
                    @error('height_cm')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Availability -->
            <div class="mb-4">
                <label for="availability" class="block text-sm font-medium text-gray-700">Availability</label>
                <select name="availability" id="availability" class="block w-full p-2 mt-1 transition border border-gray-300 rounded-lg" required>
                    <option value="in_stock" {{ $product->availability == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                    <option value="out_of_stock" {{ $product->availability == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                </select>
                @error('availability')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>



            <!-- Images -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Current Images</label>
                <div class="flex flex-wrap gap-4">
                    @foreach($product->images as $image)
                    <div class="relative">
                        <img src="{{ asset('images/uploads/product_images/' . $image->image_path) }}" alt="Product Image" class="w-32 h-32 rounded-lg">
                        <!-- Optionally add a delete button for each image -->
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- File Upload -->
            <div class="flex justify-center mb-4">
                <label class="flex flex-col items-center w-64 px-4 py-6 tracking-wide uppercase bg-white border rounded-lg shadow-lg cursor-pointer text-blue border-blue hover:bg-blue">
                    <svg class="w-8 h-8" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
                    </svg>
                    <span class="mt-2 text-base leading-normal">Select a file</span>
                    <input type='file' id="image_path" name="image_path[]" multiple class="hidden" />
                </label>
            </div>

            <button type="submit" class="px-6 py-3 text-white transition rounded-lg bg-gradient-to-r from-[#f590b0] to-[#f56e98]">Update Product</button>
        </form>
    </div>
</x-app-layout>
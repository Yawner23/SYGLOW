<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\Customer;
use App\Models\Shipping;
use Illuminate\Http\Request;
use App\Models\ChannelPayment;
use App\Models\PaymentProduct;
use App\Models\DeliveryAddress;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{

    public function uploadPayment(Request $request)
    {
        $request->validate([
            'upload_payment' => 'required|file|mimes:pdf,docx,jpg,png|max:2048',
            'payment_id' => 'required|integer',
        ]);

        $payment = Payment::findOrFail($request->payment_id);

        // Handle file upload
        if ($request->hasFile('upload_payment')) {
            $file = $request->file('upload_payment');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/payments'), $filename);

            $payment->upload_payment = $filename;
        }

        $payment->save();

        return response()->json(['success' => true]);
    }

    public function uploadshippingPayment(Request $request)
    {
        $request->validate([
            'upload_shipping_payment' => 'required|file|mimes:pdf,docx,jpg,png|max:2048',
            'payment_id' => 'required|integer',
        ]);

        $payment = Payment::findOrFail($request->payment_id);

        // Handle file upload
        if ($request->hasFile('upload_shipping_payment')) {
            $file = $request->file('upload_shipping_payment');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/payments'), $filename);

            $payment->upload_shipping_payment = $filename;
        }

        $payment->save();

        return response()->json(['success' => true]);
    }

    public function updateDate(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|integer',
            'date_of_payment' => 'required|date',
        ]);

        $payment = Payment::findOrFail($request->payment_id);
        $payment->date_of_payment = $request->date_of_payment;
        $payment->save();

        return response()->json(['success' => true]);
    }


    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $payment = Payment::with(['customer', 'deliveryAddress', 'products', 'shipping'])->findOrFail($id);
        return view('admin.payments.show', compact('payment'));
    }

    public function updateStatus(Request $request)
    {
        $payments = Payment::find($request->id);
        if ($payments) {
            $payments->status = $request->status;
            $payments->save();
        }

        return response()->json(['success' => true]);
    }

    public function index()
    {
        if (request()->ajax()) {
            $payments = Payment::with(['customer', 'deliveryAddress'])
                ->orderBy('created_at', 'desc') // Latest payments first
                ->select('*');

            return DataTables::of($payments)
                ->addIndexColumn()
                ->addColumn('status', function ($payment) {
                    $statusText = ucfirst(str_replace('_', ' ', $payment->status));
                    $checked = $payment->status === 'Paid' ? 'checked' : '';
                    return '<input type="checkbox" class="status-checkbox" data-id="' . $payment->id . '" ' . $checked . '> ' . $statusText;
                })
                ->rawColumns(['status'])
                ->make(true);
        }

        return view('admin.payments.index');
    }


    // ------------------DISTRIBUTOR SIDE---------------------------
    public function payment_summary(Request $request)
    {
        Log::info('📦 Payment Summary Request Received', [
            'data' => $request->all()
        ]);

        $validatedData = $request->validate([
            'customer_id' => 'exists:users,id',
            'delivery_address_id' => 'exists:delivery_address,id',
            'total' => 'required|numeric|min:0',
            'product_id' => 'required|array',
            'quantity' => 'required|array',
            'subtotal' => 'required|array',
            'product_id.*' => 'exists:products,id',
            'quantity.*' => 'integer|min:1',
            'voucher_code' => 'nullable|string',
            'payment_method' => 'nullable',
        ]);

        $discount = 0;

        /*
    |--------------------------------------------------------------------------
    | APPLY VOUCHER
    |--------------------------------------------------------------------------
    */
        if ($request->filled('voucher_code')) {
            $voucher = Voucher::where('code', $request->voucher_code)
                ->where('status', 'active')
                ->first();

            if (!$voucher) {
                return back()->withErrors(['voucher_code' => 'Invalid or expired voucher code.']);
            }

            $usedCount = $voucher->used_count ?? 0;

            if ($voucher->usage_limit && $usedCount >= $voucher->usage_limit) {
                return back()->withErrors(['voucher_code' => 'Voucher usage limit exceeded.']);
            }

            if ($voucher->discount_type == 'percentage') {
                $discount = $validatedData['total'] * ($voucher->discount_value / 100);
            } else {
                $discount = $voucher->discount_value;
            }

            $discount = min($discount, $validatedData['total']);
            $validatedData['total'] -= $discount;

            $voucher->used_count = $usedCount + 1;
            $voucher->save();

            Log::info('🎟 Voucher Applied', ['voucher' => $voucher->code, 'discount' => $discount]);
        }

        /*
    |--------------------------------------------------------------------------
    | COMPUTE SHIPPING WEIGHT
    |--------------------------------------------------------------------------
    */
        $address = DeliveryAddress::find($validatedData['delivery_address_id']);
        $region = $this->mapProvinceToRegion($address->province);

        $totalWeight = 0;

        foreach ($request->product_id as $index => $prodId) {
            $weight = DB::table('products_weights')->where('product_id', $prodId)->first();

            if ($weight) {

                // FIX: Detect units flexibly (Grams / Kilograms)
                $unit = strtolower(trim($weight->weight_unit));

                if (in_array($unit, ['g', 'gram', 'grams'])) {
                    $w = $weight->weights / 1000; // convert grams to kg
                } elseif (in_array($unit, ['kg', 'kilo', 'kilogram', 'kilograms'])) {
                    $w = $weight->weights;       // already in kg
                } else {
                    $w = $weight->weights;       // fallback
                }

                $totalWeight += $w * $request->quantity[$index];

                Log::info('🛒 Weight Computed', [
                    'product_id' => $prodId,
                    'raw_value' => $weight->weights . ' ' . $weight->weight_unit,
                    'converted_to_kg' => $w,
                    'quantity' => $request->quantity[$index],
                    'added_weight' => $w * $request->quantity[$index]
                ]);
            }
        }

        // Round to nearest 0.5kg
        $totalWeight = ceil($totalWeight * 2) / 2;

        $shippingFee = $this->computeShippingFee($region, $totalWeight);

        Log::info('🚚 Shipping Fee Computed', [
            'region' => $region,
            'total_weight' => $totalWeight,   // NOW CORRECT → 6.5
            'shipping_fee' => $shippingFee
        ]);



        /*
    |--------------------------------------------------------------------------
    | CREATE PAYMENT RECORD
    |--------------------------------------------------------------------------
    */
        $productTotal = array_sum($request->subtotal); // FIXED CALCULATION

        $payment = Payment::create([
            'customer_id' => $validatedData['customer_id'],
            'delivery_address_id' => $validatedData['delivery_address_id'],
            'payment_method' => 'HitPay',
            'total' => $productTotal + $shippingFee, // FIXED TOTAL = 302 + 505 = 807
            'discount' => $discount,
            'shipping_fee' => $shippingFee,
            'status' => 'unpaid',
        ]);

        Session::put('payment_id', $payment->id);

        Log::info('💰 Payment Record Created', [
            'payment_id' => $payment->id,
            'total' => $payment->total, // NOW CORRECT
            'payment_method' => $payment->payment_method,
        ]);

        /*
    |--------------------------------------------------------------------------
    | SAVE PRODUCTS
    |--------------------------------------------------------------------------
    */
        foreach ($request->product_id as $key => $productId) {
            PaymentProduct::create([
                'payment_id' => $payment->id,
                'product_id' => $productId,
                'quantity' => $request->quantity[$key],
                'subtotal' => $request->subtotal[$key],
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | SHIPPING TABLE ENTRY
    |--------------------------------------------------------------------------
    */
        Shipping::create([
            'payment_id' => $payment->id,
            'courier' => 'JNT',
            'date_of_shipping' => now(),
            'shipping_fee' => $shippingFee,
        ]);

        Log::info('📨 Shipping Record Inserted', [
            'payment_id' => $payment->id,
            'shipping_fee' => $shippingFee
        ]);

        /*
    |--------------------------------------------------------------------------
    | REDIRECT TO HITPAY
    |--------------------------------------------------------------------------
    */
        return $this->processHitpayPayment(
            $payment,
            $request->product_id,
            $request->subtotal,
            $discount,
            $shippingFee
        );
    }

    // ------------------DISTRIBUTOR SIDE---------------------------
    public function calculateShippingFee(Request $request)
    {
        Log::info('🚚 Shipping Fee Calculation Started', [
            'address_id' => $request->address_id,
            'cart' => session('cart', [])
        ]);

        $addressId = $request->address_id;
        $cart = session('cart', []);

        if (!$addressId || empty($cart)) {
            Log::warning('❌ Shipping Fee Error: Missing address or cart');
            return response()->json(['error' => 'Address or cart not found.'], 400);
        }

        $address = DeliveryAddress::find($addressId);
        if (!$address) {
            Log::warning('❌ Shipping Fee Error: Address not found', ['address_id' => $addressId]);
            return response()->json(['error' => 'Address not found.'], 404);
        }

        $region = $this->mapProvinceToRegion($address->province);

        Log::info('📍 Region Mapped', [
            'province' => $address->province,
            'region' => $region
        ]);

        // compute total weight
        $totalWeight = 0;
        foreach ($cart as $id => $item) {

            $weightData = DB::table('products_weights')->where('product_id', $id)->first();

            if ($weightData) {

                // FIXED ❗ Normalize weight unit and convert grams → kilograms properly
                $unit = strtolower(trim($weightData->weight_unit));

                if ($unit === 'grams' || $unit === 'g') {
                    $weight = $weightData->weights / 1000; // convert grams to kg
                } else { // kilograms or kg
                    $weight = $weightData->weights;
                }

                $itemTotalWeight = $weight * $item['quantity'];
                $totalWeight += $itemTotalWeight;

                Log::info('🛒 Cart Item Processed', [
                    'product_id' => $id,
                    'raw_value' => $weightData->weights . ' ' . $weightData->weight_unit,
                    'converted_to_kg' => $weight,
                    'quantity' => $item['quantity'],
                    'computed_weight' => $itemTotalWeight
                ]);
            } else {
                Log::warning('⚠ No weight record found for product', ['product_id' => $id]);
            }
        }

        $totalWeight = ceil($totalWeight * 2) / 2;
        $shippingFee = $this->computeShippingFee($region, $totalWeight);

        Log::info('✅ Shipping Fee Computed', [
            'final_weight' => $totalWeight,
            'shipping_fee' => $shippingFee
        ]);


        return response()->json([
            'shipping_fee' => $shippingFee,
            'display' => '₱' . number_format($shippingFee, 2),
            'final_weight' => $totalWeight,
            'raw_value' => $weightData->weight_unit,
        ]);
    }



    //  --------------CUSTOMER SIDE-------------------
    public function payment(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'customer_id' => 'exists:users,id',
            'delivery_address_id' => 'exists:delivery_address,id',
            'payment_method' => 'nullable|array',
            'total' => 'required|numeric|min:0',
            'product_id' => 'required|array',
            'quantity' => 'required|array',
            'subtotal' => 'required|array',
            'product_id.*' => 'exists:products,id',
            'quantity.*' => 'integer|min:1',
            'voucher_code' => 'nullable|string',
        ]);

        // Apply voucher code discount if provided
        $discount = $this->applyVoucherCode($request, $validatedData);

        // Update total after discount
        $validatedData['total'] -= $discount;

        // Create payment record (no payment_method yet)
        $payment = $this->createPaymentRecord($validatedData);

        // Store payment_id in session
        session(['payment_id' => $payment->id]);

        // Create payment products
        $this->createPaymentProducts($request, $payment->id);

        // Create shipping/order if necessary
        $sumfreight = $this->OrderQuery($payment->id);

        Shipping::create([
            'payment_id' => $payment->id,
            'courier' => 'JNT',
            'date_of_shipping' => now(),
            'shipping_fee' => $sumfreight,
        ]);

        // Collect product IDs and subtotals for HitPay
        $productIds = $validatedData['product_id'];
        $subtotals = $validatedData['subtotal'];

        // Redirect to HitPay checkout
        return $this->processHitpayPayment(
            $payment,
            $productIds,
            $subtotals,
            $discount,
            $sumfreight
        );
    }


    // public function paymongoRequest($payment_id)
    // {
    //     // Retrieve the payment details
    //     $payment = Payment::findOrFail($payment_id);

    //     // Validate if payment exists and is still unpaid
    //     if ($payment->status !== 'unpaid') {
    //         return redirect()->back()->withErrors(['error' => 'Payment is already processed or invalid.']);
    //     }

    //     // Proceed with PayMongo API request
    //     $response = Http::withHeaders([
    //         'Content-Type' => 'application/json',
    //         'accept' => 'application/json',
    //         'authorization' => 'Basic c2tfdGVzdF9YNHZ1elRQZ0Z6Q05SWWUxb3NaWnlnaGU6',
    //     ])->post('https://api.paymongo.com/v1/checkout_sessions', [
    //         'attributes' => [
    //             'line_items' => $this->buildLineItems($payment), // Build line items
    //             'payment_method_types' => explode(',', $payment->payment_method), // Convert saved methods back to array
    //             'send_email_receipt' => true,
    //             'show_description' => false,
    //             'show_line_items' => true,
    //             'cancel_url' => route('checkout.page'),
    //             'success_url' => route('payment.verify'),
    //             'statement_descriptor' => 'Your Business Name',
    //             'reference_number' => (string) $payment->id,
    //         ],
    //     ]);

    //     $responseData = $response->json();

    //     // Handle PayMongo API response
    //     if ($response->successful()) {
    //         // Update payment with PayMongo transaction ID
    //         $payment->update([
    //             'transaction_id' => $responseData['data']['id'],
    //         ]);

    //         // Redirect to PayMongo checkout URL
    //         $checkoutUrl = $responseData['data']['attributes']['checkout_url'];
    //         return redirect()->away($checkoutUrl);
    //     } else {
    //         // Handle payment errors
    //         return redirect()->back()->withErrors(['error' => $responseData['errors'][0]['detail'] ?? 'Payment processing failed.']);
    //     }
    // }


    public function payproduct(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'customer_id' => 'exists:users,id',
            'delivery_address_id' => 'exists:delivery_address,id',
            'total' => 'required|numeric|min:0',
            'product_id' => 'required|array',
            'quantity' => 'required|array',
            'subtotal' => 'required|array',
            'product_id.*' => 'exists:products,id',
            'quantity.*' => 'integer|min:1',
            'voucher_code' => 'nullable|string',
            'confirm_checkout' => 'sometimes|boolean'
        ]);

        // Apply voucher code discount if provided
        $discount = $this->applyVoucherCode($request, $validatedData);

        // Update total after applying the discount
        $validatedData['total'] -= $discount;

        // Proceed to create payment record (no payment_method anymore)
        $payment = $this->createPaymentRecord($validatedData);

        // Store payment_id in the session
        session(['payment_id' => $payment->id]);

        // Create payment products
        $this->createPaymentProducts($request, $payment->id);

        // Create an order linked to the payment
        $create = $this->createOrder($payment->id);

        // Fetch product names for the session
        $productIds = $validatedData['product_id'];
        $productNames = Product::whereIn('id', $productIds)->pluck('product_name')->toArray();

        // Store product IDs and names in session
        session([
            'product_id' => $productIds,
            'product_names' => $productNames
        ]);

        return redirect()->back()
            ->with([
                'payment_id' => $payment->id,
                'subtotal' => $validatedData['subtotal'],
                'product_id' => $productIds,
                'product_names' => $productNames,
                'message' => 'Payment processed successfully! Proceed to query your order.'
            ]);
    }

    private function mapProvinceToRegion($province)
    {
        $province = strtoupper(trim($province));

        $ncr = ['METRO MANILA', 'NCR'];
        $luzon = [
            'BATANGAS',
            'BULACAN',
            'CAVITE',
            'LAGUNA',
            'PAMPANGA',
            'QUEZON',
            'TARLAC',
            'ZAMBALES',
            'BENGUET',
            'NUEVA ECIJA',
            'ISABELA',
            'LA UNION',
            'ILOCOS NORTE',
            'ILOCOS SUR',
            'AURORA',
            'ALBAY',
            'CAMARINES SUR',
            'CAMARINES NORTE',
            'SORSOGON',
            'CATANDUANES'
        ];
        $visayas = [
            'CEBU',
            'ILOILO',
            'LEYTE',
            'BACOLOD',
            'NEGROS OCCIDENTAL',
            'NEGROS ORIENTAL',
            'BOHOL',
            'SAMAR',
            'EASTERN SAMAR',
            'WESTERN SAMAR'
        ];
        $mindanao = [
            'DAVAO DEL SUR',
            'DAVAO CITY',
            'DAVAO DEL NORTE',
            'ZAMBOANGA DEL SUR',
            'ZAMBOANGA CITY',
            'COTABATO',
            'SULTAN KUDARAT',
            'MISAMIS ORIENTAL',
            'MISAMIS OCCIDENTAL',
            'BUKIDNON',
            'AGUSAN DEL SUR',
            'AGUSAN DEL NORTE'
        ];
        $island = ['PALAWAN', 'SIQUIJOR', 'CAMIGUIN', 'BASILAN', 'SULU', 'TAWI-TAWI'];

        if (in_array($province, $ncr)) return 'NCR';
        if (in_array($province, $luzon)) return 'LUZON';
        if (in_array($province, $visayas)) return 'VISAYAS';
        if (in_array($province, $mindanao)) return 'MINDANAO';
        if (in_array($province, $island)) return 'ISLAND';

        // Default fallback
        return 'MINDANAO';
    }


    private function computeShippingFee($region, $weight)
    {
        // Handle both grams and kilograms properly
        if ($weight <= 0) {
            return 0;
        }

        // Convert grams to kg only if weight > 1000 (means it's in grams)
        $kg = $weight > 1000 ? $weight / 1000 : $weight;

        // Round up to the nearest 0.5kg step to match the rate table (approximation)
        $kg = ceil($kg * 2) / 2; // e.g. 1.2 → 1.5, 2.6 → 3.0

        // Reference shipping fee table (simplified for clarity)
        // You can extend this to 50kg using the actual image data
        $rates = [
            'MINDANAO' => [
                0.5 => 60,
                1 => 60,
                3 => 60,
                5 => 360,
                6 => 435,
                7 => 505,
                8 => 575,
                9 => 645,
                10 => 715,
                11 => 785,
                12 => 855,
                13 => 925,
                14 => 995,
                15 => 1065,
                16 => 1135,
                17 => 1205,
                18 => 1275,
                19 => 1345,
                20 => 1415,
                21 => 1485,
                22 => 1555,
                23 => 1625,
                24 => 1695,
                25 => 1765,
                26 => 1835,
                27 => 1905,
                28 => 1975,
                29 => 2045,
                30 => 2115,
                31 => 2185,
                32 => 2255,
                33 => 2325,
                34 => 2395,
                35 => 2465,
                36 => 2535,
                37 => 2605,
                38 => 2675,
                39 => 2745,
                40 => 2815,
                41 => 2885,
                42 => 2955,
                43 => 3025,
                44 => 3095,
                45 => 3165,
                46 => 3235,
                47 => 3305,
                48 => 3375,
                49 => 3445,
                50 => 3515
            ],
            'NCR' => [
                0.5 => 60,
                1 => 60,
                3 => 60,
                5 => 370,
                6 => 435,
                7 => 505,
                8 => 575,
                9 => 645,
                10 => 715,
                11 => 785,
                12 => 855,
                13 => 925,
                14 => 995,
                15 => 1065,
                16 => 1135,
                17 => 1205,
                18 => 1275,
                19 => 1345,
                20 => 1415,
                21 => 1485,
                22 => 1555,
                23 => 1625,
                24 => 1695,
                25 => 1765,
                26 => 1835,
                27 => 1905,
                28 => 1975,
                29 => 2045,
                30 => 2115,
                31 => 2185,
                32 => 2255,
                33 => 2325,
                34 => 2395,
                35 => 2465,
                36 => 2535,
                37 => 2605,
                38 => 2675,
                39 => 2745,
                40 => 2815,
                41 => 2885,
                42 => 2955,
                43 => 3025,
                44 => 3095,
                45 => 3165,
                46 => 3235,
                47 => 3305,
                48 => 3375,
                49 => 3445,
                50 => 3515
            ],
            'LUZON' => [
                0.5 => 60,
                1 => 60,
                3 => 60,
                5 => 370,
                6 => 435,
                7 => 505,
                8 => 575,
                9 => 645,
                10 => 715,
                11 => 785,
                12 => 855,
                13 => 925,
                14 => 995,
                15 => 1065,
                16 => 1135,
                17 => 1205,
                18 => 1275,
                19 => 1345,
                20 => 1415,
                21 => 1485,
                22 => 1555,
                23 => 1625,
                24 => 1695,
                25 => 1765,
                26 => 1835,
                27 => 1905,
                28 => 1975,
                29 => 2045,
                30 => 2115,
                31 => 2185,
                32 => 2255,
                33 => 2325,
                34 => 2395,
                35 => 2465,
                36 => 2535,
                37 => 2605,
                38 => 2675,
                39 => 2745,
                40 => 2815,
                41 => 2885,
                42 => 2955,
                43 => 3025,
                44 => 3095,
                45 => 3165,
                46 => 3235,
                47 => 3305,
                48 => 3375,
                49 => 3445,
                50 => 3515
            ],
            'VISAYAS' => [
                0.5 => 60,
                1 => 60,
                3 => 60,
                5 => 370,
                6 => 435,
                7 => 505,
                8 => 575,
                9 => 645,
                10 => 715,
                11 => 785,
                12 => 855,
                13 => 925,
                14 => 995,
                15 => 1065,
                16 => 1135,
                17 => 1205,
                18 => 1275,
                19 => 1345,
                20 => 1415,
                21 => 1485,
                22 => 1555,
                23 => 1625,
                24 => 1695,
                25 => 1765,
                26 => 1835,
                27 => 1905,
                28 => 1975,
                29 => 2045,
                30 => 2115,
                31 => 2185,
                32 => 2255,
                33 => 2325,
                34 => 2395,
                35 => 2465,
                36 => 2535,
                37 => 2605,
                38 => 2675,
                39 => 2745,
                40 => 2815,
                41 => 2885,
                42 => 2955,
                43 => 3025,
                44 => 3095,
                45 => 3165,
                46 => 3235,
                47 => 3305,
                48 => 3375,
                49 => 3445,
                50 => 3515
            ],
            'ISLAND' => [
                0.5 => 60,
                1 => 60,
                3 => 60,
                5 => 380,
                6 => 445,
                7 => 515,
                8 => 585,
                9 => 655,
                10 => 725,
                11 => 795,
                12 => 865,
                13 => 935,
                14 => 1005,
                15 => 1075,
                16 => 1145,
                17 => 1215,
                18 => 1285,
                19 => 1355,
                20 => 1425,
                21 => 1495,
                22 => 1565,
                23 => 1635,
                24 => 1705,
                25 => 1775,
                26 => 1845,
                27 => 1915,
                28 => 1985,
                29 => 2055,
                30 => 2125,
                31 => 2195,
                32 => 2265,
                33 => 2335,
                34 => 2405,
                35 => 2475,
                36 => 2545,
                37 => 2615,
                38 => 2685,
                39 => 2755,
                40 => 2825,
                41 => 2895,
                42 => 2965,
                43 => 3035,
                44 => 3105,
                45 => 3175,
                46 => 3245,
                47 => 3315,
                48 => 3385,
                49 => 3455,
                50 => 3525
            ],
        ];

        $region = strtoupper($region);
        if (!isset($rates[$region])) {
            $region = 'MINDANAO';
        }

        // Find closest rate equal to or higher than the weight
        foreach ($rates[$region] as $maxKg => $price) {
            if ($kg <= $maxKg) {
                return $price;
            }
        }

        // If weight exceeds 50kg, charge additional ₱70 per extra kg
        $lastRate = end($rates[$region]);
        $extraKg = ceil($kg - 50);
        return $lastRate + ($extraKg * 70);
    }


    public function getShippingFee($orderId)
    {
        $payment = Payment::with(['deliveryAddress', 'products.product'])->find($orderId);

        if (!$payment) {
            return response('Payment not found.', 404);
        }

        // 🔹 Determine region from the delivery address
        $province = optional($payment->deliveryAddress)->province;
        $region = $this->mapProvinceToRegion($province);


        // 🔹 Compute total weight
        $totalWeight = 0;
        foreach ($payment->products as $product) {
            $productModel = $product->product;
            if ($productModel && isset($productModel->weight)) {
                $weightValue = (float) $productModel->weight;
                $unit = strtolower($productModel->weight_unit ?? 'grams');

                if ($unit === 'grams' || $unit === 'g') {
                    $weightValue /= 1000; // convert to kg
                }

                $totalWeight += $weightValue * ($product->quantity ?? 1);
            }
        }

        // 🔹 Compute the shipping fee
        $shippingFee = $this->computeShippingFee($region, $totalWeight);

        // 🔹 Return a formatted value (for your JS)
        return response()->json([
            'region' => $region,
            'weight' => round($totalWeight, 2),
            'shipping_fee' => $shippingFee,
            'display' => '₱' . number_format($shippingFee, 2)
        ]);
    }

    public function processPayment(Request $request)
    {
        Log::info('ProcessPayment request received', ['request_data' => $request->all()]);

        $request->validate([
            'order_id' => 'required|string',
            'product_ids' => 'required|json',
            'subtotals' => 'required|json',
            'discount' => 'nullable|numeric',
            'voucher_code' => 'nullable|string',
            'payment_method' => 'required|string'
        ]);


        $paymentMethod = $request->input('payment_method');
        $productIds = json_decode($request->input('product_ids'), true);
        $subtotals = json_decode($request->input('subtotals'), true);
        $discount = (float)$request->input('discount', 0);
        $voucherCode = $request->input('voucher_code');
        $orderId = $request->input('order_id');

        $payment = Payment::with(['products.product', 'deliveryAddress'])->findOrFail($orderId);

        // 🔹 Get computed order details
        $orderData = $this->createOrder($payment->id);
        $productTotal = $orderData['product_total'];
        $shippingFee = $orderData['shipping_fee'];
        $valuationFee = $orderData['valuation_fee'];
        $codFee = $orderData['cod_fee'];
        $vatFee = $orderData['vat_fee'];

        // Compute grand total
        $grandTotal = ($paymentMethod === 'cod')
            ? $productTotal + $shippingFee + $valuationFee + $codFee + $vatFee - $discount
            : max(0, $productTotal + $shippingFee - $discount);

        $shippingAmount = ($paymentMethod === 'cod')
            ? $shippingFee + $valuationFee + $codFee + $vatFee
            : $shippingFee;

        Shipping::updateOrCreate(
            ['payment_id' => $payment->id],
            [
                'courier' => 'JNT',
                'date_of_shipping' => now(),
                'shipping_fee' => $shippingAmount,
            ]
        );

        // Save payment details
        $payment->update([
            'total' => $grandTotal,
            'shipping_fee' => $shippingFee,
            'discount' => $discount,
            'voucher_code' => $voucherCode,
            'product_total' => $productTotal,
            'payment_method' => ($paymentMethod === 'online') ? 'HitPay' : $paymentMethod,
            'status' => ($paymentMethod === 'cod') ? 'cod_pending' : 'pending',
        ]);

        // Increment voucher usage if used
        if ($voucherCode) {
            Voucher::where('code', $voucherCode)->increment('used_count');
            Log::info('Voucher used', ['voucher_code' => $voucherCode]);
        }

        if ($paymentMethod === 'cod') {
            // Send to J&T for COD
            $this->sendToJnt($payment);

            // Deduct stock
            foreach ($payment->products as $p) {
                if ($p->product) {
                    $p->product->decrement('quantity', $p->quantity);
                }
            }

            return redirect()->route('order_success')->with('message', 'Your COD order has been placed!');
        }
        $shippingfeedummy = "0";
        // Online (HitPay) → redirect to HitPay
        return $this->processHitpayPayment($payment, $productIds, $subtotals, $discount, $shippingfeedummy);
    }



    public function OrderQuery($id)
    {
        $payment = Payment::with(['deliveryAddress', 'products.product'])->findOrFail($id);

        // Determine region from deliveryAddress province
        $province = strtoupper(optional($payment->deliveryAddress)->province ?? 'MINDANAO');
        $region = $this->mapProvinceToRegion($province);

        // Compute total weight considering weight unit
        $totalWeight = DB::table('payment_product')
            ->join('products', 'payment_product.product_id', '=', 'products.id')
            ->join('products_weights', 'products.id', '=', 'products_weights.product_id')
            ->where('payment_product.payment_id', $id)
            ->select(DB::raw('SUM(
            CASE 
                WHEN LOWER(products_weights.weight_unit) IN ("grams", "g") 
                    THEN (products_weights.weights / 1000) * payment_product.quantity
                ELSE products_weights.weights * payment_product.quantity
            END
        ) as total_kg'))
            ->value('total_kg') ?? 0;

        $totalWeight = ceil($totalWeight * 2) / 2; // round up to nearest 0.5 kg

        // Compute shipping fee (local estimate)
        $localFee = $this->computeShippingFee($region, $totalWeight);

        // Compute product total
        $productTotal = $payment->products->sum('subtotal');
        $declaredValue = $productTotal;

        // Compute valuation, COD, and VAT fees
        $valuationFee = round($declaredValue * 0.01, 2); // 1% valuation
        $codFee = round(($declaredValue + $localFee + $valuationFee) * 0.0275, 2); // 2.75% COD fee
        $vatFee = round($codFee * 0.12, 2); // 12% VAT on COD fee

        // Get discount if any
        $discount = $payment->discount ?? 0;

        // Optional: Call J&T API for live shipping fee
        $jtFee = null;
        try {
            $data = [
                "eccompanyid" => "RWEB SOLUTIONS",
                "customerid" => "CS-V0286",
                "command" => "1",
                "serialnumber" => "98 " . $id,
            ];
            $data_digest = $this->generateDataDigest($data);
            $postData = [
                'logistics_interface' => json_encode($data),
                'data_digest' => $data_digest,
                'msg_type' => 'ORDERQUERY',
                'eccompanyid' => 'RWEB SOLUTIONS',
            ];

            $response = Http::asForm()->post(
                'https://demostandard.jtexpress.ph/jts-phl-order-api/api/order/queryOrder',
                $postData
            );

            $responseBody = json_decode($response->body(), true);
            $jtFee = $responseBody['responseitems'][0]['orderList'][0]['sumfreight'] ?? null;
        } catch (\Exception $e) {
            $jtFee = null;
            Log::error('J&T API OrderQuery failed: ' . $e->getMessage(), ['order_id' => $id]);
        }

        $shippingFee = $localFee;

        // Compute grand total including all fees minus discount
        $grandTotal = $productTotal + $shippingFee + $valuationFee + $codFee + $vatFee - $discount;

        // Log all computed values
        Log::info('OrderQuery Computation', [
            'order_id' => $id,
            'province' => $province,
            'region' => $region,
            'total_weight_kg' => $totalWeight,
            'product_total' => $productTotal,
            'shipping_fee' => $shippingFee,
            'valuation_fee' => $valuationFee,
            'cod_fee' => $codFee,
            'vat_fee' => $vatFee,
            'discount' => $discount,
            'grand_total' => $grandTotal,
        ]);

        // Return JSON formatted for frontend (COD modal or Order Summary modal)
        return response()->json([
            'order_id' => $payment->id,
            'province' => $province,
            'region' => $region,
            'weight' => round($totalWeight, 2),
            'product_total' => round($productTotal, 2),
            'shipping_fee' => round($shippingFee, 2),
            'valuation_fee' => round($valuationFee, 2),
            'cod_fee' => round($codFee, 2),
            'vat_fee' => round($vatFee, 2),
            'discount' => round($discount, 2),
            'grand_total' => round($grandTotal, 2),
            'display' => '₱' . number_format($grandTotal, 2),
        ]);
    }

    // private function processPayMongoPayment(array $productIds, array $subtotals, array $payment_method, Payment $payment, $discount, $sumfreight)
    // {
    //     // Prepare PayMongo API request
    //     $response = Http::withHeaders([
    //         'Content-Type' => 'application/json',
    //         'accept' => 'application/json',
    //         'authorization' => 'Basic c2tfdGVzdF9YNHZ1elRQZ0Z6Q05SWWUxb3NaWnlnaGU6',
    //     ])->post('https://api.paymongo.com/v1/checkout_sessions', [
    //         'data' => [
    //             'attributes' => [
    //                 'line_items' => $this->buildLineItems($productIds, $subtotals, $discount, $sumfreight), // Pass sumfreight here
    //                 'payment_method_types' => $payment_method,
    //                 'send_email_receipt' => true,
    //                 'show_description' => false,
    //                 'show_line_items' => true,
    //                 'cancel_url' => route('checkout.page'),
    //                 'success_url' => route('payment.verify'),
    //                 'statement_descriptor' => 'Your Business Name',
    //                 'reference_number' =>  (string)$payment->id,
    //             ],
    //         ],
    //     ]);

    //     $responseData = $response->json();

    //     // Handle the response from PayMongo
    //     if ($response->successful()) {
    //         $payment->update([
    //             'transaction_id' => $responseData['data']['id'],
    //         ]);
    //         Session::put('payment_id', $payment->id);

    //         $checkoutUrl = $responseData['data']['attributes']['checkout_url'];
    //         return redirect()->away($checkoutUrl);
    //     } else {
    //         return redirect()->back()->withErrors(['error' => $responseData['errors'][0]['detail'] ?? 'Payment processing failed.']);
    //     }
    // }

    // public function verify(Request $request)
    // {
    //     $paymentId = Session::get('payment_id');
    //     $payment = Payment::findOrFail($paymentId);

    //     $url = "https://api.paymongo.com/v1/checkout_sessions/{$payment->transaction_id}";
    //     $headers = [
    //         'Accept' => 'application/json',
    //         'Authorization' => 'Basic c2tfdGVzdF9YNHZ1elRQZ0Z6Q05SWWUxb3NaWnlnaGU6',
    //     ];
    //     $response = Http::withHeaders($headers)->get($url);

    //     $status = $response['data']['attributes']['payments'][0]['attributes']['status'];

    //     if ($status === 'paid') {
    //         $payment->update(['status' => 'Paid']);
    //         Session::forget('cart');

    //         // Fetch all PaymentProducts related to this payment
    //         $payment_products = PaymentProduct::where('payment_id', $paymentId)->get();
    //         $subtotals = $payment_products->pluck('subtotal');
    //         $delivery_address = DeliveryAddress::where('id', $payment->delivery_address_id)->pluck('deliver_name')->first();

    //         return view('success_purchase', [
    //             'transaction_id' => $payment->transaction_id,
    //             'payment_id' => $payment->id,
    //             'subtotals' => $subtotals,
    //             'amountPaid' => $payment->total,
    //             'delivery_address' => $delivery_address,
    //             'payment_method' => $payment->payment_method,
    //             'clearLocalStorage' => true,
    //         ]);
    //     } else {
    //         return redirect('place_order')->withErrors(['error' => 'Payment was not successful.']);
    //     }
    // }


    private function buildLineItems(array $productIds, array $subtotals, $totalDiscount, $sumfreight)
    {
        $lineItems = [];

        // Calculate the total subtotal for all items
        $totalSubtotal = array_sum($subtotals); // Calculate the total subtotal

        // Aggregate product names and calculate total final amount
        $allProductNames = [];
        $totalFinalAmount = 0;

        foreach ($productIds as $key => $productId) {
            $product = Product::find($productId);

            $itemSubtotal = intval($subtotals[$key]);

            // Calculate the proportion of the discount for this item
            $itemDiscount = ($itemSubtotal / $totalSubtotal) * $totalDiscount;

            // Calculate the final amount after discount
            $finalAmount = intval($itemSubtotal - $itemDiscount); // Ensure finalAmount is an integer

            // Aggregate product names
            $allProductNames[] = $product->product_name;

            // Accumulate the final amount
            $totalFinalAmount += $finalAmount;
        }

        // Combine all product names into a single string
        $productNamesString = implode(', ', $allProductNames);

        // Add the product line item
        $lineItems[] = [
            'name' => $productNamesString, // Combined product names
            'quantity' => 1, // Since all items are combined into one line, quantity is set to 1
            'amount' => $totalFinalAmount * 100, // Convert to cents
            'currency' => 'PHP'
        ];

        // Add the shipping cost as a separate line item
        if ($sumfreight > 0) {
            $lineItems[] = [
                'name' => 'Shipping Cost',
                'quantity' => 1,
                'amount' => intval($sumfreight) * 100, // Convert to cents
                'currency' => 'PHP'
            ];
        }

        return $lineItems;
    }

    private function applyVoucherCode(Request $request, array &$validatedData)
    {
        $discount = 0;

        if ($request->filled('voucher_code')) {
            $voucher = Voucher::where('code', $request->voucher_code)
                ->where('status', 'active')
                ->first();

            if (!$voucher || ($voucher->usage_limit && $voucher->used_count >= $voucher->usage_limit)) {
                throw new \Exception('Invalid or expired voucher code.');
            }

            $usedCount = $voucher->used_count ?? 0;
            if ($voucher->usage_limit && $usedCount >= $voucher->usage_limit) {
                throw new \Exception('Voucher usage limit exceeded.');
            }

            // Apply discount
            if ($voucher->discount_type === 'percentage') {
                $discount = $validatedData['total'] * ($voucher->discount_value / 100);
            } else {
                $discount = $voucher->discount_value;
            }

            // Ensure discount not greater than total
            $discount = min($discount, $validatedData['total']);

            // Update usage count
            $voucher->used_count = $usedCount + 1;
            $voucher->save();
        }

        return round($discount, 2);
    }

    public function validateVoucher(Request $request)
    {
        // Log the incoming request
        Log::info('Voucher Validation Request', [
            'voucher_code' => $request->voucher_code,
            'total' => $request->total,
            'user_id' => auth()->id() ?? null
        ]);

        $request->validate([
            'voucher_code' => 'required|string',
            'total' => 'required|numeric',
        ]);

        $voucher = Voucher::where('code', $request->voucher_code)
            ->where('status', 'active')
            ->first();

        if (!$voucher) {
            Log::warning('Voucher not found or inactive', [
                'voucher_code' => $request->voucher_code,
                'user_id' => auth()->id() ?? null
            ]);

            return response()->json(['success' => false, 'message' => 'Invalid voucher code.']);
        }

        if ($voucher->usage_limit && $voucher->used_count >= $voucher->usage_limit) {
            Log::warning('Voucher usage limit reached', [
                'voucher_code' => $voucher->code,
                'used_count' => $voucher->used_count,
                'usage_limit' => $voucher->usage_limit,
                'user_id' => auth()->id() ?? null
            ]);

            return response()->json(['success' => false, 'message' => 'This voucher has reached its usage limit.']);
        }

        // Calculate discount
        $discount = ($voucher->discount_type === 'percentage')
            ? $request->total * ($voucher->discount_value / 100)
            : $voucher->discount_value;

        $discount = min($discount, $request->total);

        Log::info('Voucher applied successfully', [
            'voucher_code' => $voucher->code,
            'discount' => round($discount, 2),
            'total_before_discount' => $request->total,
            'user_id' => auth()->id() ?? null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Voucher applied successfully!',
            'discount' => round($discount, 2),
            'voucher_code' => $voucher->code
        ]);
    }



    private function createPaymentRecord(array $validatedData)
    {
        $payment = new Payment();
        $payment->customer_id = $validatedData['customer_id'];
        $payment->delivery_address_id = $validatedData['delivery_address_id'];
        $payment->payment_method = null; // Always set to NULL
        $payment->total = $validatedData['total'];
        $payment->status = 'unpaid';
        $payment->save();

        return $payment;
    }


    private function createPaymentProducts(Request $request, $paymentId)
    {
        foreach ($request->product_id as $key => $productId) {
            PaymentProduct::create([
                'payment_id' => $paymentId,
                'product_id' => $productId,
                'quantity' => $request->quantity[$key],
                'subtotal' => $request->subtotal[$key],
            ]);
        }
    }


    public function createOrder($id)
    {
        $payment = Payment::with(['products.product', 'deliveryAddress'])->findOrFail($id);
        $customer = $payment->deliveryAddress;

        // Compute total weight
        $totalWeight = DB::table('payment_product')
            ->join('products', 'payment_product.product_id', '=', 'products.id')
            ->join('products_weights', 'products.id', '=', 'products_weights.product_id')
            ->where('payment_product.payment_id', $payment->id)
            ->select(DB::raw('SUM(
            CASE 
                WHEN LOWER(products_weights.weight_unit) IN ("grams", "g") 
                    THEN (products_weights.weights / 1000) * payment_product.quantity
                ELSE products_weights.weights * payment_product.quantity
            END
        ) as total_kg'))
            ->value('total_kg') ?? 0;

        $totalWeight = ceil($totalWeight * 2) / 2;

        // Compute shipping fee
        $region = strtoupper($customer->province ?? 'MINDANAO');
        $shippingFee = $this->computeShippingFee($region, $totalWeight);

        // Compute product total
        $productTotal = $payment->products->sum(fn($p) => $p->subtotal ?? 0);

        // Compute additional fees for COD
        $valuationFee = round($productTotal * 0.01, 2);
        $codFee = round(($productTotal + $shippingFee + $valuationFee) * 0.0275, 2);
        $vatFee = round($codFee * 0.12, 2);
        $discount = $payment->discount ?? 0;

        // Only compute items value (if needed)
        $itemsValue = $productTotal + $shippingFee + $valuationFee + $codFee + $vatFee - $discount;

        return [
            'payment' => $payment,
            'total_weight' => $totalWeight,
            'shipping_fee' => $shippingFee,
            'product_total' => $productTotal,
            'valuation_fee' => $valuationFee,
            'cod_fee' => $codFee,
            'vat_fee' => $vatFee,
            'items_value' => $itemsValue,
        ];
    }



    // Helper methods

    private function generateDataDigest(array $data): string
    {
        // Secret key for hashing
        $key = '354d1565d2c43af41e37a6ac5334057a';

        // Concatenate data and key, then generate MD5 hash
        $to_sign = json_encode($data) . $key;
        $md5_hash = md5($to_sign);

        // Encode the hash in Base64
        return base64_encode(strtolower($md5_hash));
    }

    private function getSenderDetails(): array
    {
        return [
            "name" => "SY GLOW",
            "postcode" => "8000",
            "mobile" => "09951455616",
            "prov" => "davao-del-sur",
            "city" => "davao-city",
            "area" => "tibungco",
            "address" => "#20 1st AVE. STA. MARIA INDUSTRIAL BAGUMBAYAN TAGUIG CITY",
        ];
    }

    private function getReceiverDetails($customer): array
    {
        if (!$customer) {
            Log::error("Receiver details not found for payment.");
            return [
                "name" => "",
                "mobile" => "",
                "prov" => "",
                "city" => "",
                "area" => "",
                "address" => "",
            ];
        }

        $province = str_replace(' ', '-', $customer['province'] ?? '');
        $city = str_replace(' ', '-', $customer['city'] ?? '');
        if (preg_match('/^city of (.+)$/i', $customer['city'] ?? '', $matches)) {
            $city = str_replace(' ', '-', $matches[1]) . '-city';
        }

        return [
            "name" => ($customer['first_name'] ?? '') . ' ' . ($customer['last_name'] ?? ''),
            "mobile" => $customer['contact_number'] ?? '',
            "prov" => $province,
            "city" => $city,
            "area" => $customer['barangay'] ?? '',
            "address" => $customer['full_address'] ?? '',
        ];
    }


    private function formatOrderItems($products): array
    {
        return $products->map(function ($item) {
            return [
                "itemname" => $item->product_name,
                "number" => $item->product_id,
                "itemvalue" => $item->subtotal ?? $item->price, // subtotal per product
            ];
        })->toArray();
    }



    public function cancel(Request $request)
    {

        return view('place_order');
    }

    private function processHitpayPayment(
        Payment $payment,
        array $productIds,
        array $subtotals,
        $discount,
        $shippingFee
    ) {
        // 🧮 Calculate product total
        $productTotal = array_sum($subtotals);

        // 🧮 Compute grand total (product total + shipping - discount)
        $grandTotal = max(0, $productTotal + $shippingFee - $discount);

        // 🔹 Update payment totals
        $payment->update([
            'total' => $grandTotal,
            'shipping_fee' => $shippingFee,
            'discount' => $discount,
            'product_total' => $productTotal,
        ]);

        // 🔹 Build HitPay line items
        $lineItems = [];

        // Product total as a single line item
        $lineItems[] = [
            'name' => 'Products Total',
            'amount' => (float) $productTotal,
        ];

        // Shipping fee
        if ($shippingFee > 0) {
            $lineItems[] = [
                'name' => 'Shipping Fee',
                'amount' => (float) $shippingFee,
            ];
        }

        // Discount (if any)
        if ($discount > 0) {
            $lineItems[] = [
                'name' => 'Discount',
                'amount' => (float) (-$discount),
            ];
        }

        // ✅ Format total with 2 decimals for HitPay
        $amount = number_format($grandTotal, 2, '.', '');

        // 🔹 URLs
        $redirectUrl = route('hitpay.verify');
        $webhookUrl = env('HITPAY_MODE') === 'sandbox'
            ? env('HITPAY_SANDBOX_WEBHOOK_URL')
            : env('HITPAY_LIVE_WEBHOOK_URL');

        try {
            // Log payload before sending
            Log::info('HitPay Payload', [
                'reference_number' => $payment->id,
                'amount' => $amount,
                'line_items' => $lineItems,
            ]);

            $response = Http::withHeaders([
                'X-BUSINESS-API-KEY' => env('HITPAY_API_KEY'),
                'accept' => 'application/json',
            ])->post(env('HITPAY_URL') . '/payment-requests', [
                'amount'           => $amount,
                'currency'         => 'PHP',
                'reference_number' => (string) $payment->id,
                'redirect_url'     => $redirectUrl,
                'webhook'          => $webhookUrl,
                'purpose'          => 'Order Payment #' . $payment->id,
                'items'            => $lineItems,
            ]);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['id'], $responseData['url'])) {
                $payment->update(['transaction_id' => $responseData['id']]);
                Session::put('payment_id', $payment->id);

                return redirect()->away($responseData['url']);
            }

            $errorMessage = $responseData['message'] ?? 'HitPay processing failed.';
            return redirect()->back()->withErrors(['error' => $errorMessage]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'HitPay Error: ' . $e->getMessage()]);
        }
    }





    public function verifyHitpayPayment(Request $request)
    {
        $paymentId = Session::get('payment_id');
        Log::info("🔹 Verifying HitPay payment", ['payment_id' => $paymentId]);

        $payment = Payment::with('products.product')->findOrFail($paymentId);
        Log::info("🔹 Payment fetched", ['payment_id' => $payment->id, 'transaction_id' => $payment->transaction_id]);

        $response = Http::withHeaders([
            'X-BUSINESS-API-KEY' => env('HITPAY_API_KEY'),
            'accept' => 'application/json',
        ])->get(env('HITPAY_URL') . '/payment-requests/' . $payment->transaction_id);

        Log::info("🔹 HitPay API Response", ['status' => $response->status(), 'body' => $response->body()]);

        $responseData = $response->json();

        if ($response->successful() && $responseData['status'] === 'completed') {
            Log::info("✅ HitPay payment completed", ['payment_method' => $responseData['payment_method'] ?? 'HitPay']);

            // 🔹 Update payment: only product total + shipping fee
            $productTotal = $payment->products->sum(fn($p) => $p->subtotal ?? 0);
            $shippingFee = $payment->shipping->shipping_fee ?? 0;
            $discount = $payment->discount ?? 0;

            $hitpayTotal = $productTotal + $shippingFee - $discount;

            $payment->update([
                'status' => 'Paid',
                'payment_method' => $responseData['payment_method'] ?? 'HitPay',
                'total' => $hitpayTotal,
            ]);

            Log::info("🔹 Payment status updated to Paid (HitPay amount only)", [
                'payment_id' => $payment->id,
                'total' => $payment->total
            ]);

            // 🔹 Deduct stock
            foreach ($payment->products as $p) {
                if ($p->product) {
                    $oldQuantity = $p->product->quantity;
                    $p->product->decrement('quantity', $p->quantity);
                    Log::info("🔹 Product stock decremented", [
                        'product_id' => $p->product->id,
                        'old_quantity' => $oldQuantity,
                        'quantity_deducted' => $p->quantity,
                        'new_quantity' => $p->product->fresh()->quantity
                    ]);
                }
            }

            Session::forget('cart');
            Log::info("🔹 Cart cleared from session");

            // 🔹 Send order to J&T (full fees will still be included)
            try {
                $this->sendToJnt($payment);
                Log::info("✅ Sent order to J&T", ['payment_id' => $payment->id]);
            } catch (\Exception $e) {
                Log::error("❌ Failed to send J&T order", ['payment_id' => $payment->id, 'error' => $e->getMessage()]);
            }

            // 🔹 Prepare success_purchase view
            $payment_products = PaymentProduct::where('payment_id', $paymentId)->get();
            $subtotals = $payment_products->pluck('subtotal');
            $delivery_address = DeliveryAddress::where('id', $payment->delivery_address_id)
                ->pluck('deliver_name')
                ->first();

            Log::info("🔹 Preparing success_purchase view", [
                'payment_id' => $payment->id,
                'amount_paid' => $payment->total,
                'delivery_address' => $delivery_address
            ]);

            return view('success_purchase', [
                'transaction_id'    => $payment->transaction_id,
                'payment_id'        => $payment->id,
                'subtotals'         => $subtotals,
                'amountPaid'        => $payment->total,
                'delivery_address'  => $delivery_address,
                'payment_method'    => $payment->payment_method,
                'clearLocalStorage' => true,
            ]);
        } else {
            Log::warning("❌ HitPay payment not successful - deleting order", [
                'payment_id' => $payment->id,
                'response_status' => $response->status(),
                'response_body' => $response->body()
            ]);

            // ❗ DELETE PAYMENT + PAYMENT PRODUCTS
            PaymentProduct::where('payment_id', $payment->id)->delete();
            $payment->delete();

            Session::forget('payment_id');
            Session::forget('cart');

            return redirect('place_order')->withErrors([
                'error' => 'HitPay Payment was not successful. Order has been removed.'
            ]);
        }
    }




    private function sendToJnt($payment)
    {
        try {
            // 🔹 Fetch customer and related products
            $customer = $payment->customers($payment->id, $payment->delivery_address_id)->first();
            $products = $payment->createorder($payment->id);

            // 🔹 Compute total weight using SQL join
            $totalWeight = DB::table('payment_product')
                ->join('products', 'payment_product.product_id', '=', 'products.id')
                ->join('products_weights', 'products.id', '=', 'products_weights.product_id')
                ->where('payment_product.payment_id', $payment->id)
                ->select(DB::raw('SUM(
                CASE 
                    WHEN LOWER(products_weights.weight_unit) IN ("grams", "g") 
                        THEN (products_weights.weights / 1000) * payment_product.quantity 
                    ELSE products_weights.weights * payment_product.quantity 
                END
            ) as total_kg'))
                ->value('total_kg') ?? 0;

            $totalWeight = ceil($totalWeight * 2) / 2; // round up to nearest 0.5 kg

            // 🔹 Compute shipping fee
            $region = strtoupper($customer->province ?? 'MINDANAO');
            $shippingFee = $this->computeShippingFee($region, $totalWeight);

            // 🔹 Compute total quantity
            $totalQuantity = $products->sum(fn($p) => $p->quantity ?? 1);

            // 🔹 Compute product total
            $productTotal = $products->sum(fn($p) => $p->subtotal ?? 0);

            // 🔹 Compute additional fees
            $valuationFee = round($productTotal * 0.01, 2); // 1% valuation
            $codFee = round(($productTotal + $shippingFee + $valuationFee) * 0.0275, 2); // 2.75% COD
            $vatFee = round($codFee * 0.12, 2); // 12% VAT
            $discount = $payment->discount ?? 0;

            // 🔹 Compute items value including all fees
            // 🔹 Compute items value based on payment method
            if (strtolower($payment->payment_method) === 'hitpay') {
                $itemsValue = $productTotal + $shippingFee - $discount;
            } else { // COD or others
                $itemsValue = $productTotal + $shippingFee + $valuationFee + $codFee + $vatFee - $discount;
            }

            // 🔹 Prepare payload for J&T
            $data = [
                "actiontype" => "add",
                "environment" => "production",
                "eccompanyid" => "RWEB SOLUTIONS",
                "customerid" => "CS-V0286",
                "txlogisticid" => "98 " . $payment->id,
                "ordertype" => "1",
                "servicetype" => "6",
                "deliverytype" => "1",
                "sender" => $this->getSenderDetails(),
                "receiver" => $this->getReceiverDetails($customer),
                "createordertime" => now()->toDateTimeString(),
                "paytype" => "1", // COD
                "weight" => round($totalWeight, 2),
                "itemsvalue" => round($itemsValue, 2),
                "totalquantity" => $totalQuantity,
                "items" => $this->formatOrderItems($products),
                "shippingfee" => round($shippingFee, 2),
                "valuation_fee" => round($valuationFee, 2),
                "cod_fee" => round($codFee, 2),
                "vat_fee" => round($vatFee, 2),
            ];

            Log::info('J&T Order Payload', [
                'payment_id' => $payment->id,
                'data' => $data,
            ]);

            // 🔹 Generate data digest
            $data_digest = $this->generateDataDigest($data);

            // 🔹 Prepare POST data
            $postData = [
                'logistics_interface' => json_encode($data),
                'data_digest' => $data_digest,
                'msg_type' => 'ORDERCREATE',
                'eccompanyid' => 'RWEB SOLUTIONS',
            ];

            Log::info('J&T POST Data', [
                'payment_id' => $payment->id,
                'postData' => $postData,
            ]);

            // 🔹 Send POST request
            $response = Http::asForm()->post(
                'https://demostandard.jtexpress.ph/jts-phl-order-api/api/order/create',
                $postData
            );

            Log::info('J&T API Response', [
                'payment_id' => $payment->id,
                'status' => $response->status(),
                'body' => $response->body(),
                'json' => $response->json(),
            ]);

            // 🔹 SAVE MAILNO RETURNED BY J&T
            $responseData = $response->json();
            $mailno = $responseData['responseitems'][0]['mailno'] ?? null;

            if ($mailno) {
                $payment->update([
                    'tracking_number' => $mailno,
                ]);
            } else {
                // still save shipping even if no tracking number
                $payment->update([
                    'upload_shipping_payment' => $shippingFee,
                ]);
            }

            return $response->body();
        } catch (\Exception $e) {
            Log::error('❌ J&T API Error: ' . $e->getMessage(), ['payment_id' => $payment->id]);
            return null;
        }
    }
}

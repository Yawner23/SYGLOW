<?php

namespace App\Http\Controllers;

use App\Models\Blogs;
use App\Models\Banner;
use App\Models\Rating;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Category;
use App\Models\Partners;
use App\Models\Wishlist;
use App\Models\Distributor;
use App\Models\ProductType;
use Illuminate\Http\Request;
use App\Models\PaymentProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function index()
    {
        $banner = Banner::all();
        $partners = Partners::all();

        $products = Product::select(
            'products.id',
            'products.product_name',
            'products.availability',
            'products.created_at',
            'products.updated_at',
            'product_prices.price as product_price',
            DB::raw('COALESCE(AVG(CASE WHEN ratings.status = "verified" THEN ratings.rating ELSE NULL END), 0) as averageRating')
        )
            ->where('products.status', 'new_arrival') // Filter by status
            ->leftJoin('payment_product', 'products.id', '=', 'payment_product.product_id')
            ->leftJoin('payment', 'payment_product.payment_id', '=', 'payment.id')
            ->leftJoin('ratings', 'payment.id', '=', 'ratings.payment_id')
            ->leftJoin('product_prices', 'products.id', '=', 'product_prices.product_id') // Join with product_prices table
            ->where('product_prices.consumer_id', 5) // Filter by specific consumer_id
            ->groupBy(
                'products.id',
                'products.product_name',
                'products.availability',
                'products.created_at',
                'products.updated_at',
                'product_prices.price'
            )
            ->get();


        $hot_selling_products = Product::select(
            'products.id',
            'products.product_name',
            'products.availability',
            'products.created_at',
            'products.updated_at',
            'product_prices.price as product_price',
            DB::raw('COALESCE(AVG(CASE WHEN ratings.status = "verified" THEN ratings.rating ELSE NULL END), 0) as averageRating')
        )
            ->leftJoin('payment_product', 'products.id', '=', 'payment_product.product_id') // Join with payment_product
            ->leftJoin('payment', 'payment_product.payment_id', '=', 'payment.id')
            ->leftJoin('ratings', 'payment.id', '=', 'ratings.payment_id')
            ->leftJoin('product_prices', 'products.id', '=', 'product_prices.product_id') // Join with product_prices table
            ->where('product_prices.consumer_id', 5) // Filter by specific consumer_id
            ->whereNotNull('payment_product.product_id') // Ensure product has been sold (exists in payment_product)
            ->groupBy(
                'products.id',
                'products.product_name',
                'products.availability',
                'products.created_at',
                'products.updated_at',
                'product_prices.price'
            )
            ->get();


        return view('welcome', compact('banner', 'partners', 'products', 'hot_selling_products'));
    }
    public function blogs(Request $request)
    {
        // Get the search query and category filter from the request
        $search = $request->input('search');
        $categoryFilter = $request->input('category');

        // Build the query for blogs
        $query = Blogs::query();

        // Apply category filter if provided
        if ($categoryFilter) {
            $query->whereIn('category_id', $categoryFilter);
        }

        // Apply search filter if provided
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('short_description', 'like', '%' . $search . '%');
            });
        }

        // Paginate the filtered blogs (10 items per page)
        $blogs = $query->paginate(10);

        // Get all categories for the filter sidebar
        $categories = Category::all();

        // Return view or JSON response based on AJAX request
        if ($request->ajax()) {
            $blogsHtml = view('partials.blogs', compact('blogs'))->render();
            return response()->json(['blogsHtml' => $blogsHtml, 'currentPage' => $blogs->currentPage(), 'lastPage' => $blogs->lastPage()]);
        }

        return view('blogs', compact('blogs', 'categories'));
    }


    public function blogs_details($id)
    {
        $blogs = Blogs::findOrFail($id);

        // Fetch the latest blog posts excluding the current one
        $recentBlogs = Blogs::where('id', '!=', $id)
            ->orderBy('created_at', 'desc')
            ->take(2) // Adjust the number of recent posts you want to display
            ->get();

        // Fetch all categories
        $category = Category::all();


        return view('blogs_details', compact('blogs', 'recentBlogs', 'category'));
    }


    public function wishlist()
    {
        // Check if the user is authenticated
        if (auth()->check()) {
            $customerId = auth()->user()->id;

            // Fetch wishlist items for the authenticated user
            $wishlist = Wishlist::where('customer_id', $customerId)
                ->with(['product' => function ($query) {
                    $query->select(
                        'products.id',
                        'products.product_name',
                        'products.availability',
                        'product_prices.price as product_price'
                    )
                        ->leftJoin('product_prices', 'products.id', '=', 'product_prices.product_id')
                        ->where('product_prices.consumer_id', 5); // Adjust this as needed
                }])
                ->get();
        } else {
            // Handle the case where the user is not authenticated
            $wishlist = collect(); // Empty collection or handle as needed
        }

        return view('wishlist', compact('wishlist'));
    }

    public function about_us()
    {
        return view('about_us');
    }
    public function reset_password()
    {
        return view('reset_password');
    }
    public function be_our_member()
    {
        return view('be_our_member');
    }
    public function be_our_member_distributors()
    {
        return view('be_our_member_distributors');
    }
    public function be_our_member_sign_up(Request $request)
    {
        $query = Distributor::query();

        // Filter by search term if present
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by region if present
        if ($request->has('region') && $request->region) {
            $query->where('region', $request->region);
        }

        // Filter by province if present
        if ($request->has('province') && $request->province) {
            $query->where('province', $request->province);
        }

        // Filter by city if present
        if ($request->has('city') && $request->city) {
            $query->where('city', $request->city);
        }

        // Get the filtered results
        $be_our_member = $query->get();

        // Get unique regions, provinces, and cities for the dropdowns
        $regions = Distributor::distinct()->pluck('region');
        $provinces = Distributor::distinct()->pluck('province');
        $cities = Distributor::distinct()->pluck('city');

        return view('be_our_member_sign_up', compact('be_our_member', 'regions', 'provinces', 'cities'));
    }

    public function products(Request $request)
    {
        $query = Product::select(
            'products.id',
            'products.product_name',
            'products.availability',
            'products.quantity',
            'products.created_at',
            'products.updated_at',
            'products_weights.weights',
            'products_weights.weight_unit',
            DB::raw('COALESCE(product_prices.price, 0) as product_price'),
            DB::raw('COALESCE(product_prices.discount_price, 0) as discount_price'), // ✅ Add this
            DB::raw('COALESCE(AVG(CASE WHEN ratings.status = "verified" THEN ratings.rating ELSE NULL END), 0) as averageRating')
        )
            ->leftJoin('payment_product', 'products.id', '=', 'payment_product.product_id')
            ->leftJoin('payment', 'payment_product.payment_id', '=', 'payment.id')
            ->leftJoin('ratings', 'payment.id', '=', 'ratings.payment_id')
            ->leftJoin('products_weights', 'products.id', '=', 'products_weights.product_id')
            ->leftJoin('product_prices', function ($join) {
                $join->on('products.id', '=', 'product_prices.product_id')
                    ->where('product_prices.consumer_id', '=', 5); // Customer only
            })
            ->groupBy(
                'products.id',
                'products.product_name',
                'products.availability',
                'products.quantity',
                'products.created_at',
                'products.updated_at',
                'products_weights.weights',
                'products_weights.weight_unit',
                'product_prices.price',
                'product_prices.discount_price' // ✅ Also group by discount_price
            );


        // Apply sorting
        if ($request->input('sort') === 'price_asc') {
            $query->orderBy('product_prices.price', 'asc');
        } elseif ($request->input('sort') === 'price_desc') {
            $query->orderBy('product_prices.price', 'desc');
        }

        // Apply filters (category, product type)
        if ($request->has('category')) {
            $selectedCategories = $request->input('category');
            $query->whereIn('product_types.category_id', $selectedCategories)
                ->join('product_types', 'products.product_type_id', '=', 'product_types.id');
        }
        if ($request->has('product_type')) {
            $selectedProductTypes = $request->input('product_type');
            $query->whereIn('product_types.id', $selectedProductTypes)
                ->join('product_types', 'products.product_type_id', '=', 'product_types.id');
        }

        // Pagination
        $products = $query->paginate(18);

        if ($request->ajax()) {
            return response()->json([
                'products' => $products,
            ]);
        }

        // Count in-stock and out-of-stock products
        $in_stock = Product::where('availability', 'in_stock')->count();
        $out_of_stock = Product::where('availability', 'out_of_stock')->count();

        // Fetch all categories and product types for filters
        $category = Category::all();
        $product_types = ProductType::all();

        return view('products', compact('products', 'category', 'product_types', 'in_stock', 'out_of_stock'));
    }
    public function products_details($id)
    {
        // Fetch the product along with prices, ratings, and review comments
        $product = Product::with([
            'prices' => function ($query) {
                $query->where('consumer_id', 5);
            },
        ])->findOrFail($id);

        // Fetch products with the same product_type_id, excluding the current product
        $relatedProducts = Product::where('product_type_id', $product->product_type_id)
            ->where('id', '!=', $id) // Exclude the current product
            ->get();

        // Fetch review comments by passing the product ID
        $reviewComments = $product->reviewComments($product->id);

        // Collect related skin types and benefits
        $selected_skin_types = $product->skinTypes->pluck('id')->toArray();
        $selected_benefits = $product->benefits->pluck('id')->toArray();


        // Fetch all the products associated with the payment using PaymentProduct model
        $paymentProducts = PaymentProduct::whereHas('payment', function ($query) use ($id) {
            $query->where('product_id', $id);
        })->get();

        return view('products_details', compact('product', 'relatedProducts', 'selected_skin_types', 'selected_benefits',  'paymentProducts', 'reviewComments'));
    }



    public function contact_us()
    {
        return view('contact_us');
    }
    public function login()
    {
        return view('login');
    }

    public function forget_password()
    {
        return view('forget_password');
    }

    public function order_success()
    {
        return view('order_success'); // you can reuse the same Blade file
    }
    public function success_page()
    {
        return view('success_page');
    }
    public function success_purchase()
    {
        return view('success_purchase');
    }
    public function otp()
    {
        return view('otp');
    }
    public function create_account()
    {
        return view('create_account');
    }
    public function distributor_profile()
    {
        return view('distributor_profile');
    }
    public function distributor_edit_profile()
    {
        return view('distributor_edit_profile');
    }

    public function getUplinesAndDownlines(Request $request)
    {
        $currentDistributor = Auth::user()->distributor;
        $currentDistributorType = $currentDistributor->distributor_type;
        $currentDistributorCode = $currentDistributor->code;
        $order = $this->getDistributorOrder();
        $currentLevel = $order[$currentDistributorType];

        // Get the search query from the request
        $search = $request->input('search', '');
        $currentUser = Auth::user()->id;

        // Fetch upline distributors
        $uplines = Distributor::whereIn('distributor_type', function ($query) use ($currentLevel, $order) {
            $query->select('distributor_type')
                ->from('distributors')
                ->whereIn('distributor_type', array_keys(array_filter($order, fn($level) => $level <= $currentLevel)));
        })
            ->where('user_id', $currentDistributorCode)
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            })
            ->get();

        $currentUser = Auth::user()->id;
        // Fetch downline distributors
        $downlines = Distributor::whereIn('distributor_type', function ($query) use ($currentLevel, $order) {
            $query->select('distributor_type')
                ->from('distributors')
                ->whereIn('distributor_type', array_keys(array_filter($order, fn($level) => $level > $currentLevel)));
        })
            ->where('code', $currentUser)
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            })
            ->get();

        return view('distributor_list', compact('uplines', 'downlines'));
    }

    // Helper function to get distributor types' order
    private function getDistributorOrder()
    {
        return [
            '1' => 1,
            '2' => 2,
            '3' => 3,
            '4' => 4,
        ];
    }

    public function distributor_id()
    {
        return view('distributor_id');
    }
    public function distributor_purchase(Request $request)
    {
        $user = auth()->user();
        $distributorType = $user->distributor?->distributor_type ?? 'N/A';
        $query = Product::select(
            'products.id',
            'products.product_name',
            'products.availability',
            'products.created_at',
            'products.updated_at',
            'product_prices.price as product_price',
            DB::raw('COALESCE(AVG(ratings.rating), 0) as averageRating')
        )
            ->leftJoin('payment_product', 'products.id', '=', 'payment_product.product_id')
            ->leftJoin('payment', 'payment_product.payment_id', '=', 'payment.id')
            ->leftJoin('ratings', 'payment.id', '=', 'ratings.payment_id')
            ->leftJoin('product_prices', 'products.id', '=', 'product_prices.product_id')
            ->where('product_prices.consumer_id', $distributorType) // Filter by the user's distributor_type
            ->groupBy('products.id', 'products.product_name', 'products.created_at', 'products.updated_at', 'products.availability', 'product_prices.price');

        // Sorting
        if ($request->has('sort') && !empty($request->sort)) {
            $sort = $request->sort;
            $query->orderBy('product_prices.price', $sort);
        }

        // Searching
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('products.product_name', 'like', "%$search%");
        }

        // Filtering by Category
        if ($request->has('category') && !empty($request->category)) {
            $categories = $request->category;
            $query->whereIn('product_types.category_id', $categories)
                ->join('product_types', 'products.product_type_id', '=', 'product_types.id');
        }

        $products = $query->get();

        $category = Category::all();

        $in_stock = $products->where('availability', 'in_stock')->count();
        $out_of_stock = $products->where('availability', 'out_of_stock')->count();

        return view('distributor_purchase', compact('products', 'category', 'in_stock', 'out_of_stock'));
    }

    public function distributor_payment_summary($id)
    {
        $payment = Payment::with(['products', 'shipping'])->findOrFail($id); // Assuming you have a 'shipping' relation

        return view('distributor_payment_summary', compact('payment'));
    }
    public function distributor_cart()
    {
        return view('distributor_cart');
    }
    public function distributor_check_out()
    {
        return view('distributor_check_out');
    }


    public function distributor_ordered_items($customer_id)
    {
        $payments = Payment::where('customer_id', $customer_id)->with('products')->get();

        return view('distributor_ordered_items', compact('payments'));
    }
    public function distributor_applied_distributor(Request $request)
    {
        // Get the search query from the request
        $search = $request->input('search');

        // Query distributors with search functionality
        $distributors = Distributor::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            })
            ->get();

        // Aggregate counts
        $totalApplications = $distributors->count();
        $pendingCount = $distributors->where('user.status', 'pending')->count();
        $approvedCount = $distributors->where('user.status', 'active')->count();
        $rejectedCount = $distributors->where('user.status', 'inactive')->count();

        return view('distributor_applied_distributor', compact('distributors', 'totalApplications', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }

    public function customer_profile()
    {
        return view('customer_profile');
    }

    public function customer_ordered_items($customer_id)
    {
        // Fetch payments for the given customer ID, include related products, order by latest
        $payments = Payment::where('customer_id', $customer_id)
            ->with('products')
            ->orderBy('created_at', 'desc') // latest first
            ->get();

        // Return the view with the payments data
        return view('customer_ordered_items', compact('payments'));
    }

    public function customer_reviews($id)
    {
        $payment = Payment::with('products.product.images')->findOrFail($id);

        // Check if a review already exists for the given payment
        $existingReview = Rating::where('payment_id', $id)->exists();

        return view('customer_reviews', [
            'payment' => $payment,
            'existingReview' => $existingReview,
        ]);
    }

    public function customer_edit_profile()
    {
        $user = Auth::user();
        $delivery_address = $user->delivery_address; // Assuming a `deliveryAddresses` relationship exists

        return view('customer_edit_profile', compact('delivery_address'));
    }
}

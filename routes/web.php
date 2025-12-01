<?php

use App\Models\Wishlist;
use App\Models\ProductType;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BlogsController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PartnersController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\ReferralCodeController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\Auth\RegisteredUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Routes for forgot password functionality
Route::get('/forget_password', [PageController::class, 'forget_password']);
Route::post('/forgot-password', [PasswordController::class, 'sendOtp'])->name('password.email');
Route::get('/otp', [PasswordController::class, 'showOtpForm'])->name('otp.show');
Route::post('/otp', [PasswordController::class, 'verifyOtp'])->name('otp.verify');
Route::get('/reset_password', [PageController::class, 'reset_password']);
Route::post('/password-reset', [PasswordController::class, 'updatePassword'])->name('password.update');

Route::post('/send-otp', [VerificationController::class, 'send_otp'])->name('send.otp');
Route::post('/delivery_verify-otp', [VerificationController::class, 'verify_otp'])->name('delivery_verify');


Route::get('/customer_reviews/{id}', [PageController::class, 'customer_reviews']);
Route::get('/', [PageController::class, 'index']);
Route::get('/about_us', [PageController::class, 'about_us']);
Route::get('/be_our_member', [PageController::class, 'be_our_member']);
Route::get('/be_our_member_distributors', [PageController::class, 'be_our_member_distributors']);
Route::get('/be_our_member_sign_up', [PageController::class, 'be_our_member_sign_up']);
Route::get('/products', [PageController::class, 'products']);
Route::get('/products_details/{id}', [PageController::class, 'products_details']);
Route::get('/contact_us', [PageController::class, 'contact_us']);
Route::get('/order-success', [PageController::class, 'order_success'])->name('order_success');
Route::get('/success_page', [PageController::class, 'success_page']);
Route::get('/success_purchase', [PageController::class, 'success_purchase']);
Route::get('/otp', [PageController::class, 'otp']);
Route::get('/create_account', [PageController::class, 'create_account']);
Route::get('/blogs', [PageController::class, 'blogs'])->name('blogs');
Route::get('/blogs_details/{id}', [PageController::class, 'blogs_details']);
Route::get('/register', [RegisteredUserController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'register']);
Route::post('register', [VerificationController::class, 'store'])->name('register');
Route::get('verify-otp', [VerificationController::class, 'showOtpForm']);
Route::post('verify-otp', [VerificationController::class, 'verifyOtp'])->name('verify-otp');


Route::group(['middleware' => ['permission:roles_and_privileges']], function () {
    Route::resource('admin/roles', RoleController::class);
});

Route::group(['middleware' => ['permission:user_management']], function () {
    Route::resource('admin/user_management', UserManagementController::class);
    Route::post('admin/user_management/update_status', [UserManagementController::class, 'updateStatus'])->name('user_management.update_status');
    Route::get('user_management/export', [UserManagementController::class, 'export'])->name('user_management.export');
});
Route::group(['middleware' => ['permission:code']], function () {
    Route::resource('admin/code', ReferralCodeController::class);
});
Route::group(['middleware' => ['permission:voucher']], function () {
    Route::resource('admin/voucher', VoucherController::class);
});
Route::group(['middleware' => ['permission:banner']], function () {
    Route::resource('admin/banners', BannerController::class);
});

Route::group(['middleware' => ['permission:partners']], function () {
    Route::resource('admin/partners', PartnersController::class);
});

Route::group(['middleware' => ['permission:contacts']], function () {
    Route::resource('admin/contact_us', ContactUsController::class);
});


Route::post('/contact_us/store', [ContactUsController::class, 'store_contact_us'])->name('contacts.store_contact_us');
Route::group(['middleware' => ['permission:category']], function () {
    Route::resource('admin/categories', CategoryController::class);
});

Route::group(['middleware' => ['permission:product_types']], function () {
    Route::resource('admin/product_type', ProductTypeController::class);
});

Route::group(['middleware' => ['permission:products']], function () {
    Route::resource('admin/products', ProductController::class);
    Route::post('admin/products/update-status', [ProductController::class, 'updateStatus'])->name('products.updateStatus');
});

Route::group(['middleware' => ['permission:reviews']], function () {
    Route::resource('admin/reviews', ReviewController::class);
    Route::post('admin/reviews/update_status', [ReviewController::class, 'updateStatus'])->name('reviews.update_status');
});
Route::group(['middleware' => ['permission:blogs']], function () {
    Route::resource('admin/blogs', BlogsController::class);
});

Route::group(['middleware' => ['permission:payments']], function () {
    Route::resource('admin/payments', PaymentController::class);
    Route::post('admin/payments/update_status', [PaymentController::class, 'updateStatus'])->name('payments.updateStatus');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments/{payment}/shipping', [ShippingController::class, 'create'])->name('shipping.create');
    Route::post('/payments/{payment}/shipping', [ShippingController::class, 'store'])->name('shipping.store');
});

Route::group(['middleware' => ['permission:customer']], function () {
    Route::get('/customer_profile', [PageController::class, 'customer_profile']);
    Route::get('/customer_ordered_items/{customer_id}', [PageController::class, 'customer_ordered_items']);
    Route::get('/cancel_product', [PaymentController::class, 'cancel'])->name('payment.cancel');
    Route::get('/verify_product', [PaymentController::class, 'verify'])->name('payment.verify');
    Route::post('/add_wishlist/{product_id}/{customer_id}', [WishlistController::class, 'store'])->name('wishlist.store');

    // 🧾 Payment-related routes
    Route::get('/wishlist', [PageController::class, 'wishlist'])->name('view.wishlist');
    Route::post('/payproduct', [PaymentController::class, 'payproduct'])->name('your.payment.route');
    Route::get('/order-query/{id}', [PaymentController::class, 'OrderQuery']);
    Route::post('/process-payment', [PaymentController::class, 'processPayment'])->name('process.payment');
    Route::get('/get-shipping-fee/{orderId}', [PaymentController::class, 'getShippingFee'])->name('get.shipping.fee');
    Route::post('/validate-voucher', [PaymentController::class, 'validateVoucher'])->name('voucher.validate');
    // COD Payment Route
    Route::post('/payment/jnt', [PaymentController::class, 'processJNTPayment'])->name('payment.jnt');

    // HitPay Routes
    Route::get('/payment/hitpay/verify', [PaymentController::class, 'verifyHitpayPayment'])->name('hitpay.verify');
    Route::post('/payment/hitpay/webhook', [PaymentController::class, 'hitpayWebhook'])->name('hitpay.webhook');


    Route::post('save_cart', [CartController::class, 'saveCart'])->name('cart.save');
    Route::get('place_order', [CartController::class, 'showCheckoutPage'])->name('checkout.page');
    Route::get('/profile', [ProfileController::class, 'edit_profile'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update_customer'])->name('profile.update');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
});

Route::group(['middleware' => ['permission:customer,distributor']], function () {
    Route::get('/customer_edit_profile', [PageController::class, 'customer_edit_profile']);
    Route::post('/delivery-address', [CustomerController::class, 'store'])->name('delivery-address.store');
    Route::delete('/delivery-address/{id}', [CustomerController::class, 'destroy'])->name('delivery-address.destroy');
});

Route::group(['middleware' => ['permission:distributor']], function () {
    Route::get('/distributor_ordered_items/{customer_id}', [PageController::class, 'distributor_ordered_items']);
    Route::get('/distributor_check_out', [PageController::class, 'distributor_check_out'])->name('payment.success');
    Route::get('/distributor_payment_summary/{id}', [PageController::class, 'distributor_payment_summary']);
    Route::post('/calculate-shipping-fee', [PaymentController::class, 'calculateShippingFee'])->name('calculate.shipping_fee');
    Route::post('/payment_summary', [PaymentController::class, 'payment_summary'])->name('save.payment_summary');
    Route::get('/checkout', [CartController::class, 'showCheckout'])->name('checkout');
    Route::post('/payments/upload-payment', [PaymentController::class, 'uploadPayment'])->name('payments.uploadPayment');
    Route::post('/payments/upload-shipping-payment', [PaymentController::class, 'uploadshippingPayment'])->name('payments.uploadshippingPayment');
    Route::post('/payments/update-date', [PaymentController::class, 'updateDate'])->name('payments.updateDate');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::get('/distributor_profile', [PageController::class, 'distributor_profile']);
    Route::get('/distributor_edit_profile', [PageController::class, 'distributor_edit_profile']);
    Route::put('/profile/update_distributor/{id}', [ProfileController::class, 'update_distributor'])->name('profile.update_distributor');
    Route::get('/distributor_id', [PageController::class, 'distributor_id']);
    Route::get('/distributor_purchase', [PageController::class, 'distributor_purchase']);
    Route::get('/distributor_cart', [CartController::class, 'viewCart'])->name('distributor_cart');
    Route::get('/distributor_applied_distributor', [PageController::class, 'distributor_applied_distributor']);
    Route::get('/distributor_list', [PageController::class, 'getUplinesAndDownlines'])->name('distributors.uplines-downlines');

    // --- HitPay Routes ---
    Route::post('/hitpay/pay', [PaymentController::class, 'hitpay'])->name('hitpay.pay');
    Route::get('/hitpay/verify', [PaymentController::class, 'verifyHitpay'])->name('hitpay.verifycheckout');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



require __DIR__ . '/auth.php';

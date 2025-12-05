<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payment';
    protected $fillable = [
        'customer_id',
        'delivery_address_id',
        'payment_method',
        'total',
        'status',
        'label_status',
        'transaction_id',
        'tracking_number',
        'date_of_payment',
        'upload_payment',
        'upload_shipping_payment',
        'jt_response_body',
        'jt_post_data'
    ];

    // Define the relationship to the Customer (User) model
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // Define the relationship to the DeliveryAddress model
    public function deliveryAddress()
    {
        return $this->belongsTo(DeliveryAddress::class, 'delivery_address_id');
    }

    // Define the relationship to the PaymentProduct model
    public function products()
    {
        return $this->hasMany(PaymentProduct::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'payment_id');
    }

    public function shipping()
    {
        return $this->hasOne(Shipping::class, 'payment_id');
    }
    public function customers($payment_id, $delivery_address_id)
    {
        return Payment::select(
            'customers.first_name',
            'customers.last_name',
            'customers.contact_number',
            'delivery_address.province',
            'delivery_address.city',
            'delivery_address.barangay',
            'delivery_address.full_address',
        )
            ->join('users', 'payment.customer_id', '=', 'users.id')
            ->join('customers', 'users.id', '=', 'customers.user_id')
            ->join('delivery_address', 'customers.user_id', '=', 'delivery_address.customer_id')
            ->where('payment.id', '=', $payment_id)
            ->where('delivery_address.id', '=', $delivery_address_id)
            ->get();
    }

    public function createorder($payment_id)
    {
        return Payment::select(
            'payment.total',
            'payment_product.quantity',  // Fetch quantity from payment_product
            'payment_product.subtotal',
            'products_weights.weights',
            'product_prices.price',
            'products.product_name',
            'products.id as product_id',
        )
            ->join('payment_product', 'payment.id', '=', 'payment_product.payment_id')
            ->join('products', 'payment_product.product_id', '=', 'products.id')
            ->join('products_weights', 'products.id', '=', 'products_weights.product_id')
            ->join('product_prices', 'products.id', '=', 'product_prices.product_id')
            ->where('payment.id', $payment_id)  // No need for double condition on payment_id
            ->where('product_prices.consumer_id', 5)  // Assuming consumer_id is fixed for filtering
            ->get();
    }
}

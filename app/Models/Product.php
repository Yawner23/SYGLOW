<?php

namespace App\Models;

use App\Models\ProductType;
use App\Models\ProductImage;
use App\Models\ProductWeight;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'product_type_id',
        'product_name',
        'product_description',
        'availability',
        'status',
        'shelf_life',
        'volume',
        'edition',
        'product_form',
        'quantity',
        'seller_sku'
    ];

    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }
    public function weights()
    {
        return $this->hasMany(ProductWeight::class);
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
    public function ratings()
    {
        return $this->hasManyThrough(Rating::class, Payment::class, 'product_id', 'payment_id');
    }
    public function paymentProducts()
    {
        return $this->hasMany(PaymentProduct::class);
    }
    public function averageRating()
    {
        return $this->hasManyThrough(Rating::class, PaymentProduct::class, 'product_id', 'payment_id', 'id', 'payment_id')
            ->avg('rating');
    }
    public function reviewComments($productId)
    {
        return ReviewComment::select(
            'review_comments.*',
            'customers.*',
            'ratings.*'
        )
            ->join('ratings', 'review_comments.rating_id', '=', 'ratings.id') // Join with ratings
            ->join('payment', 'ratings.payment_id', '=', 'payment.id') // Join with payment
            ->join('users', 'payment.customer_id', '=', 'users.id') // Join with customers
            ->join('customers', 'customers.user_id', '=', 'users.id') // Join with customers
            ->join('payment_product', 'payment.id', '=', 'payment_product.payment_id') // Join with payment_product
            ->where('payment_product.product_id', '=', $productId) // Filter by the product ID
            ->where('ratings.status', '=', 'verified')
            ->get();
    }


    public function prices()
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function dimensions()
    {
        return $this->hasOne(ProductDimension::class);
    }
    public function packTypes()
    {
        return $this->hasOne(PackType::class);
    }

    public function skinTypes()
    {
        return $this->belongsToMany(SkinType::class, 'product_skin_types');
    }

    public function benefits()
    {
        return $this->belongsToMany(Benefit::class, 'product_benefits');
    }
}

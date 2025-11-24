<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentProduct extends Model
{
    use HasFactory;
    protected $table = 'payment_product';
    protected $fillable = [
        'payment_id',
        'product_id',
        'quantity',
        'subtotal',
    ];

    // Define the relationship to the Payment model
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    // Define the relationship to the Product model
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

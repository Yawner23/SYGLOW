<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductWeight extends Model
{
    use HasFactory;
    protected $table = 'products_weights';
    protected $fillable = ['product_id', 'weights', 'weight_unit'];
    public function weight()
    {
        return $this->belongsTo(Product::class);
    }
}

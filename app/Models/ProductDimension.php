<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDimension extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'length_cm', 'width_cm', 'height_cm'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

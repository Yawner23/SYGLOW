<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBenefit extends Model
{
    use HasFactory;

    protected $table = 'product_benefits';
    protected $fillable = ['product_id', 'benefit_id'];
}

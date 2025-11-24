<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSkinType extends Model
{
    use HasFactory;
    protected $table = 'product_skin_types';
    protected $fillable = ['product_id', 'skin_type_id'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkinType extends Model
{
    use HasFactory;
    protected $table = 'skin_types';
    protected $fillable = ['skin_type'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_skin_types');
    }
}

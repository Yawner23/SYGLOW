<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackType extends Model
{
    use HasFactory;
    protected $table = 'pack_types';
    protected $fillable = ['product_id', 'pack_type', 'quantity_per_pack'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

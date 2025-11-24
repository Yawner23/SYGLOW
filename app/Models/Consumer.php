<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consumer extends Model
{
    use HasFactory;

    protected $fillable = [
        'consumer_name',
    ];

    public function prices()
    {
        return $this->hasMany(ProductPrice::class);
    }
}

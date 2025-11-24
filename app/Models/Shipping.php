<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;

    protected $table = 'shipping';
    protected $primaryKey = 'id';
    protected $fillable = [
        'payment_id',
        'courier',
        'date_of_shipping',
        'shipping_fee',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}

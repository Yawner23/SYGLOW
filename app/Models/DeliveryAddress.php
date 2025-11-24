<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryAddress extends Model
{
    use HasFactory;

    protected $table = 'delivery_address';
    protected $fillable = ['customer_id', 'deliver_name', 'full_address', 'province', 'city', 'barangay', 'zip_code', 'email_address', 'delivery_instructions', 'contact_no', 'tel_no'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function deliveryAddress()
    {
        return $this->hasMany(Payment::class);
    }
}

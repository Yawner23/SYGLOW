<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'first_name', 'last_name', 'contact_number', 'date_of_birth', 'profile_picture', 'referral_code'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

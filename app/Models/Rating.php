<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    protected $table = 'ratings';

    // Specify the fields that can be mass-assigned
    protected $fillable = [
        'payment_id',
        'rating',
    ];

    // Define the relationship with the ReviewComment model
    public function reviewComment()
    {
        return $this->hasOne(ReviewComment::class);
    }

    // Define the relationship with the Payment model
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}
